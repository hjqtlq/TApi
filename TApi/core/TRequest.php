<?php
/**
 *
 * @author TLQ
 *        
 */
class TRequest extends TBase
{
    public $actionVar = 'a';
    public $controllerVar = 'c';
    public $moduleVar = 'm';
    public $versionVar = 'v';
    
    public $data = array();
    
    public function init($params = array())
    {
        $this->data = $params;
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }
    
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}