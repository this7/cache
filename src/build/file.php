<?php
/**
 * @Author: Administrator
 * @Date:   2017-03-29 10:24:07
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-07 14:36:25
 */
namespace this7\cache\build;
use Exception;

/**
 * 文件缓存处理
 * Class File
 */
class file implements base {

    /**
     * 缓存目录
     * @var [type]
     */
    private $dir;

    public function __construct() {
        $this->dir = ROOT_DIR . DS . C('cache', 'FileCachePath');
        $this->connect();
    }

    /**
     * 设置缓存链接
     * @return [type] [description]
     */
    public function connect() {
        if (!is_dir($this->dir) && !to_mkdir($this->dir, '', true, false)) {
            throw new Exception("缓存目录创建失败");
        }
    }

    /**
     * 设置缓存目录
     * @param  [type] $dir [description]
     * @return [type]      [description]
     */
    public function dir($dir) {
        if (is_dir($dir) || to_mkdir($dir, '', true, false)) {
            $this->dir = $dir;
        }
        return $this;
    }

    /**
     * 缓存文件
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    private function getFile($name) {
        return $this->dir . '/' . md5($name) . ".php";
    }

    /**
     * 设置
     * @param [type]  $name   [description]
     * @param [type]  $data   [description]
     * @param integer $expire [description]
     */
    public function set($name, $data, $expire = 3600) {
        $file = $this->getFile($name);

        #缓存时间
        $expire = sprintf("%010d", $expire);

        $data = "<?php\n//" . $expire . serialize($data) . "\n?>";

        return file_put_contents($file, $data);
    }

    /**
     * 检查是否有效
     * @param  string $name [description]
     * @return [type]       [description]
     */
    public function check($name) {
        $file = $this->getFile($name);

        #缓存文件不存在
        if (!is_file($file)) {
            return false;
        }

        $content = file_get_contents($file);

        $expire = intval(substr($content, 8, 10));

        #修改时间
        $mtime = filemtime($file);

        #缓存失效处理
        if ($expire > 0 && $mtime + $expire < time()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 获取
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function get($name) {
        $file = $this->getFile($name);

        #缓存文件不存在
        if (!is_file($file)) {
            return NULL;
        }

        $content = file_get_contents($file);

        $expire = intval(substr($content, 8, 10));

        #修改时间
        $mtime = filemtime($file);

        #缓存失效处理
        if ($expire > 0 && $mtime + $expire < time()) {
            return @unlink($file);
        }

        return unserialize(substr($content, 18, -3));
    }

    /**
     * 删除
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function del($name) {
        $file = $this->getFile($name);

        return is_file($file) && unlink($file);
    }

    /**
     * 刷新缓存池
     * @return [type] [description]
     */
    public function flush() {
        return on_del($this->dir);
    }
}