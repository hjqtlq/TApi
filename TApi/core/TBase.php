<?php
/**
 * TBase.php
 * 基础文件
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TBase框架基类
 * 框架中除去TApi和T类中，其他所有文件的基类
 * 定义了常用的魔术方法，一些常用的方法
 * 
 * @method init 初始化方法，当使用createClass方式创建的时候会默认调用此方法
 * @method getValue 从数组或者对象中获取属性，如果没有返回默认值
 * 
 * @version 1.0.0
 * @since 1.0.0
 */
class TBase
{
    public function __get($name)
    {
        $getMethodName = 'get' . ucfirst($name);
        if(method_exists($this, $getMethodName)) {
            return $this->{$name}();
        }
        return null;
    }
    
    public function __set($name, $value)
    {
        $setMethodName = 'set' . ucfirst($name);
        if(method_exists($this, $setMethodName)) {
            return $this->{$name}();
        }
        return null;
    }
    
    public function __isset($name)
    {
        $hasMethodName = 'has' . ucfirst($name);
        if(method_exists($this, $hasMethodName)) {
            return $this->{$name}();
        }
        return null;
    }
    
    public function __call($name, $args)
    {
        if(strpos('get', $name)) {
            $proName = lcfirst(ltrim($name, 'get'));
            return isset($this->{$proName}) ? $this->{$proName} : null;
        }
    }
    
    public static function __callstatic($name, $args)
    {
        
    }
    
    public function __destruct()
    {
        
    }
    
    public function getApp()
    {
        
    }
    
    public function init($params = null) {}
    
    public function getValue($data, $key, $default = null)
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }
}