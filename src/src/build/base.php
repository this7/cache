<?php
/**
 * @Author: Administrator
 * @Date:   2017-03-29 10:24:07
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-07 14:36:27
 */
namespace this7\cache\build;

/**
 * 缓存处理接口
 * Interface InterfaceCache
 */
interface base {
    public function connect();

    public function set($name, $value, $expire);

    public function get($name);

    public function del($name);

    public function flush();
}