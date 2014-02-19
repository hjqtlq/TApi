<?php
class TConfig extends TBase
{
    public $config;
 
    public function __construct($array)
    {
        $this->config = $array;
    }
    public function __get($name)
    {
        if(isset($this->config[$name])) {
            return is_array($this->config[$name]) ?
                new TConfig($this->config[$name]) :
                $this->config[$name];
        }
    }
}
