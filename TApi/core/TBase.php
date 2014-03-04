<?php
/**
 *
 * @author TLQ
 *        
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