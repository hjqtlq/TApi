<?php
class TController extends TBase
{
    public $actionId;
    public $actionMethodName;
    public $controllerClassName;
    
    public function init($scheduler = null)
    {
        $this->actionId = $scheduler->getActionId();
        $this->actionMethodName = $scheduler->getActionMethodName();
        $this->controllerClassName = $scheduler->getControllerClassName();
        $this->realVersion = $scheduler->getVersionController()->getRealVersion();
    }
    
    public function __call($name, $args)
    {
        throw new TRequestException();
    }
    
    public static function getName()
    {
        return $this->controllerClassName;
    }
    
    public static function getPath()
    {
        
    }
    
    public static function getAlias($version = null)
    {
        
    }
    
    
    public function getActionMethodName()
    {
        return $this->actionMethodName;
    }
    
    public function run()
    {
        if(false === $this->_beforeAction()) {
            throw new TException();
        }
        var_dump($this->getActionMethodName());
        $action = $this->getActionMethodName();
        if(!method_exists($this, $action)) {
            throw new TRequestException();
        }
        call_user_func(array($this, $action));
        $this->_afterAction();
    }
    
    private function _beforeAction()
    {
        $this->beforeAction();
    }
    
    public function beforeAction(){}
    
    private function _afterAction()
    {
        TResponse::send();
        $this->afterAction();
    }
    
    public function afterAction(){}
}