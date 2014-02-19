<?php
class TController extends TBase
{
    public function getControllerName()
    {
    }
    
    public function getControllerPath()
    {
        
    }
    
    public function getActionName()
    {
        
    }
    
    public function run()
    {
        if(false === $this->_beforeAction()) {
            throw new TException();
        }
//         T::import('')
        call_user_method($this->getActionName() . 'Action', $this);
        $this->_afterAction();
    }
    
    private function _beforeAction()
    {
        $this->beforeAction();
    }
    
    public function beforeAction(){}
    
    private function _afterAction()
    {
        $this->_afterAction();
    }
    
    public function afterAction(){}
}