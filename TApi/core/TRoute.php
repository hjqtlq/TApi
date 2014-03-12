<?php
/**
 * TRoute.php
 *
 * 路由文件
 *
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TRoute
 *
 * 路由控制类，用来控制请求路由
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class TRoute extends TBase
{
    /**
     * @var string 默认action ID
     */
    public $defaultActionId = 'index';
    /**
     * @var string 默认controller ID
     */
    public $defaultControllerId = 'index';
    /**
     * @var string 默认module ID
     */
    public $defaultModuleId = 'home';
    /**
     * @var string 请求路由中action的参数名称
     */
    public $actionVar = 'a';
    /**
     * @var string 请求路由中controller的参数名称
     */
    public $controllerVar = 'c';
    /**
     * @var string 请求路由中module的参数名称
     */
    public $moduleVar = 'm';
    /**
     * @var string 请求路由中version的参数名称
     */
    public $versionVar = 'v';
    /**
     * @var TVersionController 版本控制器实例
     */
    public $versionController;
    
    /**
     * @var TModule 模块实例
     */
    public $module;
    /**
     * @var string 当前请求的module ID
     */
    public $moduleId;
    /**
     * @var string 当前请求的module路径别名
     */
    public $moduleAlias;
    
    /**
     * @var TController 控制器实例
     */
    public $controller;
    /**
     * @var string 当前请求的controller ID
     */
    public $controllerId;
    /**
     * @var string 当前请求controller名称，完整的controller类称
     */
    public $controllerClassName;
    /**
     * @var string controller路径别名
     */
    public $controllerAlias;
    
    /**
     * @var string 当前请求的action ID
     */
    public $actionId;
    /**
     * @var string 当前请求的action方法名称，完整的action方法名
     */
    public $actionMethodName;
    
    /**
     * @var string 当前请求的版本号
     */
    public $version;
    /**
     * @var string 经过版本控制器处理后对应真实的文件版本号
     */
    public $realVersionString;
    
    /**
     * @var TRequest TRequest实例
     */
    public $request;
    
    public function init($params = array())
    {
        $this->request = TApi::createClass('TRequest');
        $this->prosessRequest();
    }
    
    /**
     * 处理请求
     * 
     * 从请求中解析出对应的ID
     * @throws TRequestException 当解析请求失败时抛出
     */
    public function prosessRequest()
    {
        if(isset(TApi::getConfig()->route)) {
            foreach(TApi::getConfig()->route->config as $key => $value) {
                if(property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
        $this->moduleId = $this->request->get($this->moduleVar, $this->defaultModuleId);
        $this->controllerId = $this->request->get($this->controllerVar, $this->defaultControllerId);
        $this->actionId = $this->request->get($this->actionVar, $this->defaultActionId);
        
        $this->controllerClassName = $this->controllerId . 'Controller';
        $this->actionMethodName = $this->actionId . 'Action';
        
        $this->versionController = TApi::createClass('TVersionController');
        $this->version = $this->versionController->getVersion();
        $this->realVersionString = $this->versionController->getRealVersion(array('toString' => true));
    }
    
    public function getModuleId()
    {
        return $this->moduleId;
    }
    
    public function getControllerId()
    {
        return $this->controllerId;
    }
    
    public function getControllerClassName()
    {
        return $this->controllerClassName;
    }
    
    public function getActionId()
    {
        return $this->actionId;
    }
    
    public function getActionMethodName()
    {
        return $this->actionMethodName;
    }
}