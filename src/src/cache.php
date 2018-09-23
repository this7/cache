<?php
/**
 * this7 PHP Framework
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright 2016-2018 Yan TianZeng<qinuoyun@qq.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.ub-7.com
 */
namespace this7\cache;
/**
 * 缓存
 */
class cache {

    /**
     * 应用
     * @var [type]
     */
    public $app;

    /**
     * 连接
     * @var [type]
     */
    protected $connect;

    public function __construct($app) {
        $this->app     = $app;
        $driver        = __NAMESPACE__ . '\build\\' . C('cache', 'type');
        $this->connect = new $driver;
    }

    /**
     * 更改缓存驱动
     * @param  [type] $driver [description]
     * @return [type]         [description]
     */
    public function driver($driver) {
        $driver        = __NAMESPACE__ . '\build\\' . ucfirst($driver);
        $this->connect = new $driver;
        return $this;
    }

    public function __call($method, $params) {
        if (method_exists($this->connect, $method)) {
            return call_user_func_array([$this->connect, $method], $params);
        } else {
            return $this;
        }
    }
}