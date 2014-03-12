<?php
/**
 * TApplication.php
 * 
 * API应用文件
 * 
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TApplication
 * 
 * 应用基础类，该类为API运行时请求处理以及控制
 * 
 * FIXME 需要考虑是否需要将默认参数是否放入到TRouter里面，并且持有TVersionController
 * 的一个实例。通过Route来控制并且得到所有的路由信息。Application只是用来处理和路由
 * 无关的基础信息，例如获取框架的路径、语言、创建插件、得到基础插件（TRoute也是一个插件）
 * TRoute持有的信息包括但不限于：
 *  - 默认值：defaultActionId、defaultXXXXX
 *  - 参数：actionVar、xxxVar
 *  - 路径别名：moduleAlias、xxxAlias
 * 如果这样实现，没有决定的是：
 *  - 谁持有module和controller的实例？TRoute还是TApplication？
 *  - 如果是TApplication持有module，那么TRoute如何使用module里面的getModuleAlias
 *    来获取模块的路径（我觉得有必要让module来独立实现获取路径别名的功能，因为根据模
 *    还需要来做其他的事情，比如引入只有使用module才需要的公共文件）
 *  - 如何调用到路由里面的值方便？
 * 
 * @property string $defaultActionId
 * @property string $defaultControllerId
 * @property string $actionVar
 * @property string $controllerVar
 * @property string $moduleVar
 * @property string @versionVar
 * @property TVersionController $versionController
 * @property TModule $module
 * @property string $moduleId
 * 
 * FIXME ...回头再弄：）
 * 
 * @version 1.0.0
 * @since 1.0.0
 */
 
class TApplication extends TBase
{
    /**
     * @var TModule 模块实例
     */
    public $module;
    /**
     * @var string 当前请求的module路径别名
     */
    public $moduleAlias;
    
    /**
     * @var TController 控制器实例
     */
    public $controller;
    /**
     * @var string controller路径别名
     */
    public $controllerAlias;
    
    /**
     * 运行API
     * @throws TRequestException 当处理请求失败抛出
     */
    public function run()
    {
        //FIXME 这里需要些版本验证逻辑
        if(true !== $this->praseRequest()) {
            throw new TRequestException();
        }
        $this->createController($this->getRoute())->run();
    }
    
    /**
     * @return TRoute
     */
    public function getRoute()
    {
        return TApi::createClass('TRoute');
    }
    
    /**
     * 处理请求数据
     * 
     * 得到controller的别名路径。如果使用module则得到module实例和module的别名路径
     * 
     * TODO 未来对request的处理都在这里进行
     * 
     * @return boolean 如果处理成功返回true，失败返回false
     */
    public function praseRequest()
    {
        // TODO FIX，需要验证参数是否合法以及是否完整
        if($this->usingModule()) {
            $this->moduleAlias = $this->getModule()->getModuleAlias();
            $this->controllerAlias = $this->getModule()->getControllerAlias();
        } else {
            $this->controllerAlias = TController::getAlias();
        }
        return true;
    }
    
    /**
     * 创建控制器
     * 
     * FIXME 这里在创建控制器的时候还需要做其他的处理，例如before或者after以及一些验证等
     * 
     * @return multitype:
     */
    public function createController($route)
    {
        return TApi::createClass($this->getControllerAlias(), $route);
    }
    
    /**
     * 初始化函数
     * 
     * 初始化各种参数，但是考虑是否需要改到TRoute里面去处理此类问题，在这里只是简单的
     * 持有一个TRoute的实例
     * 
     * @see TBase::init()
     */
    public function init($params = null)
    {
        $this->module = TApi::createClass('TModule', $this->getRoute());
    }
    
    public function getModule()
    {
        return $this->module;
    }
    
    public function getModuleAlias($moduleName = null, $version = null)
    {
        return $this->getModule()->getModuleAlias($moduleName = null, $version = null);
    }
    
    public function getControllerAlias($controllerId = null, $moduleName = null, $version = null)
    {
        if($this->usingModule()) {
            return $this->getModule()->getControllerAlias();
        }
        return '@controller.' . $this->addVersionPath() . $this->getRoute()->getControllerClassName($controllerId);
    }

    public function usingModule()
    {
        return $this->getRoute()->getModuleId() ? true : false;
    }

	/**
     * @return the $_version
     */
    public function getVersion()
    {
        return $this->version;
    }

	/**
     * @param field_type $_version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}