<?php
/**
 * TConfig.php
 * 
 * 获取配置文件
 * 
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TConfig
 * 
 * 获取配置文件，提供按照对象的方式获取配置文件
 * @example:
 *  $config = new TConfig(array('user' => array('name' => 'T.L.Q.')));
 *  echo $config->user->name;
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class TConfig extends TBase
{
    public $config;
 
    public function __construct($array)
    {
        $this->config = $array;
    }
    public function __get($name)
    {
        if(!isset($this->config[$name])) {
            return null;
        }
        return is_array($this->config[$name]) ?
            new TConfig($this->config[$name]) :
            $this->config[$name];
    }

    public function __isset($name)
    {
        return isset($this->config[$name]);
    }
}
