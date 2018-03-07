<?php
/**
 * @Author: isglory
 * @E-mail: admin@ubphp.com
 * @Date:   2016-09-09 15:36:28
 * @Last Modified by:   qinuoyun
 * @Last Modified time: 2018-03-07 14:36:21
 * Copyright (c) 2014-2016, UBPHP All Rights Reserved.
 */
namespace this7\cache\build;

use Exception;

/**
 * Redis缓存处理类
 * Class Redis
 */
class redis implements base {

    protected $obj;

    /**
     * 连接
     * @return [type] [description]
     */
    public function connect() {
        $host      = C('system', 'redis_host');
        $port      = C('system', 'redis_port');
        $password  = C('system', 'redis_password');
        $database  = C('system', 'redis_database');
        $this->obj = new Redis();

        if ($this->obj->connect($host, $port)) {
            throw new Exception("Redis 连接失败");
        }

        $this->obj->auth($password);
        $this->obj->select($database);
    }

    /**
     * 设置
     * @param [type]  $name   [description]
     * @param [type]  $value  [description]
     * @param integer $expire [description]
     */
    public function set($name, $value, $expire = 3600) {

        if ($this->obj->set($name, $value)) {
            return $this->obj->expire($name, $expire);
        }
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
        return $this->obj->del($name);
    }

    /**
     * 刷新缓存
     * @return [type] [description]
     */
    public function flush() {
        return $this->obj->flushall();
    }
}