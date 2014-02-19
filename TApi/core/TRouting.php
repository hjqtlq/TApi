<?php
/**
 *
 * @author TLQ
 *        
 */
class TRouting extends TBase
{
    public $moduleName;
    public $modulePath;
    
    public $controllerName;
    public $controllerPath;
    
    public $actionName;
    public $actionPath;
    
    public $version;
    
    public function init()
    {
        
    }
    
    public function createController()
    {
        T::createClass($this->controllerName);
    }
    
    public function createModule()
    {
        
    }
}