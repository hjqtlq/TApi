<?php
/**
 * TController.php
 *
 * 控制器文件
 *
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TController
 *
 * 控制器基类，所有controller都需要继承自改类
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class TController extends TBase
{
    /**
     * @var string action id
     */
    public $actionId;
    /**
     * @var string action方法名称
     */
    public $actionMethodName;
    /**
     * @var string controller类名
     */
    public $controllerClassName;
    
    public function init($application = null)
    {
        $this->actionId = $application->getActionId();
        $this->actionMethodName = $application->getActionMethodName();
        $this->controllerClassName = $application->getControllerClassName();
        $this->realVersion = $application->getVersionController()->getRealVersion();
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
    
    /**
     * 运行action方法
     * @throws TException 当beforeAction返回false的时候会抛出
     * @throws TRequestException 当没有找到对应的action的时候抛出
     */
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
        return $this->beforeAction();
    }
    
    public function beforeAction()
    {
        return true;
    }
    
    private function _afterAction()
    {
        TResponse::send();
        return $this->afterAction();
    }
    
    public function afterAction()
    {
        return true;
    }
}