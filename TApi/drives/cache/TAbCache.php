<?php
/**
 *
 * @author TLQ
 *        
 */
abstract class TAbCache extends TBase
{
    private static $_instances = array();
    public $perfix = '';
    public $hashKey = false;
    public $hashKeyMethod = 'md5';
    
    public static function instance()
    {
        $className = get_class($this);
        if(!isset(self::$_instances[$className])) {
            self::$_instances[$className] = new $className;
        }
        return self::$_instances[$className];
    }
    abstract public function disconnect();
    abstract public function get($key) {}
    abstract public function set($key, $value, $ttl = 0) {}
    abstract public function add($key, $value, $ttl = 0) {}
    abstract public function setMulti($key, $values, $ttl = 0) {}
    abstract public function getMulti($keys) {}
    abstract public function delete($key, $delay = 0) {}
    abstract public function increment($key, $num = 1) {}
    abstract public function decrement($key, $num = 1) {}
    abstract public function flush($delay) {}
    
    public function __destruct()
    {
        $this->disconnect();
    }
}