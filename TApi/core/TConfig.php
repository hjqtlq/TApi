<?php
class TConfig extends TBase
{
    private $_config;
 
    public function __construct($array)
    {
        $this->_config = $array;
    }
    public function __get($name)
    {
        if(!isset($this->_config[$name])) {
            return null;
        }
        return is_array($this->_config[$name]) ?
            new TConfig($this->_config[$name]) :
            $this->_config[$name];
    }

    public function __isset($name)
    {
        return isset($this->_config[$name]);
    }
}
