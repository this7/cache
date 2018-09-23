<?php
/**
 * @Author: Administrator
 * @Date:   2017-03-29 10:24:07
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-07 14:36:24
 */
namespace this7\cache\build;
use Exception;

/**
 * Memcache缓存处理类
 * Class Memcache
 */
class memcache implements base {

    protected $obj;

    public function __construct() {
        $this->connect();
    }

    /**
     * 连接
     * @return [type] [description]
     */
    public function connect() {
        $host = C('system', 'memcache_host');
        $port = C('system', 'memcache_port');
        if ($this->obj = new Memcache()) {
            $this->obj->addServer($host, $port);
        } else {
            throw new Exception("Memcache 连接失败");
        }
    }

    /**
     * 设置
     * @param [type]  $name   [description]
     * @param [type]  $value  [description]
     * @param integer $expire [description]
     */
    public function set($name, $value, $expire = 3600) {
        return $this->obj->set($name, $value, 0, $expire);
    }

    /**
     * 获得
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function get($name) {
        return $this->obj->get($name);
    }

    /**
     * 删除
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function del($name) {
        return $this->obj->delete($name);
    }

    /**
     * 刷新缓存池
     * @return [type] [description]
     */
    public function flush() {
        return $this->obj->flush();
    }

}