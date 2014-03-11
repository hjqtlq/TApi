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
     * @var string 默认action ID
     */
    public $defaultActionId = 'index';
    /**
     * @var string 默认controller ID
     */
    public $defaultControllerId = 'site';
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
    
    /**
     * 运行API
     * @throws TRequestException 当处理请求失败抛出
     */
    public function run()
    {
        //TODO FIX 这里需要些版本验证逻辑
        if(true !== $this->praseRequest()) {
            throw new TRequestException();
        }
        $this->createController()->run();
    }
    
    /**
     * 处理请求数据
     * 
     * 得到controller的别名路径。如果使用module则得到module实例和module的别名路径
     * 
     * 未来对request的处理都在这里进行
     * 
     * @return boolean 如果处理成功返回true，失败返回false
     */
    public function praseRequest()
    {
        // TODO FIX，需要验证参数是否合法以及是否完整
//         $this->controllerClassName = $this->getControllerId() . 'Controller';
//         $this->actionMethodName = $this->getActionId() . 'Action';
//         $this->version = $this->getVersionController()->getRealVersion();
//         $this->versionString = $this->getVersionController()->getVersion(true);
        if($this->usingModule()) {
            $module = $this->getModule();
            $this->moduleAlias = $module->getModuleAlias();
            $this->controllerAlias = $module->getControllerAlias();
        } else {
            $this->controllerAlias = TController::getAlias();
        }
        return true;
    }
    
    /**
     * 得到request实例中的值
     * 
     * 代理方法，方便获取request的值
     * 
     * @param string $key request中的值
     * @return mixed 如果值不存在返回null，存在返回对应的值
     */
    public function getRequest($key = null)
    {
        return $this->request->get($key);
    }
    
    /**
     * 得到版本控制器
     * 
     * TODO 需要考虑是否要将版本控制器放入UrlManage里面去
     * 
     * @return TVersionController
     */
    public function getVersionController()
    {
        if(empty($this->versionController)) {
            $this->versionController = TApi::createClass('TVersionController');
        }
        return $this->versionController;
    }
    
    /**
     * 创建控制器
     * 
     * FIXME 这里在创建控制器的时候还需要做其他的处理，例如before或者after以及一些验证等
     * 
     * @return multitype:
     */
    public function createController()
    {
        return TApi::createClass($this->getControllerAlias(), $this);
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
        $this->request = TApi::createClass('TRequest', $_REQUEST);
        // TODO just a demo,init config
        if(isset(TApi::getConfig()->application)) {
            $applicationConfig = TApi::getConfig()->application;
            if(isset($applicationConfig->actionVar)) {
                $this->actionVar = $applicationConfig->actionVar;
            }
        }
        $this->moduleId = $this->request->{$this->moduleVar};
        $this->module = TApi::createClass('TModule', $this);
        
        $controllerId = $this->request->{$this->controllerVar};
        $this->controllerId = empty($controllerId) ? $this->defaultControllerId : ucfirst($controllerId);
        $this->controllerClassName = $this->controllerId . 'Controller';
        
        $actionId = $this->request->{$this->actionVar};
        $this->actionId = empty($actionId) ? $this->defaultActionId : strtolower($actionId);
        $this->actionMethodName = $this->actionId . 'Action';
        
        $this->versionController = TApi::createClass('TVersionController');
        $this->version = $this->versionController->getVersion();
        $this->realVersionString = $this->versionController->getRealVersion(array('toString' => true));
    }
    
    public function getModule()
    {
        return $this->module;
    }
    
    public function getModuleId($moduleId = null)
    {
        return $this->moduleId;
    }

    /**
     * @param field_type $_moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }
    
    public function getModuleAlias($moduleName = null, $version = null)
    {
        return $this->getModule()->getModuleAlias($moduleName = null, $version = null);
    }
    
    public function getControllerId($controllerId = null)
    {
        return is_null($controllerId) ? $this->getRequest($this->controllerVar) : $controllerId;
    }
    
    public function getControllerClassName($controllerId = null)
    {
        $controllerId = is_null($controllerId) ? $this->getControllerId($controllerId) : $controllerId;
        return $controllerId . 'Controller';
    }
    
    public function getActionMethodName($actionId = null)
    {
        if(empty($actionId)) {
            return $this->actionMethodName;
        }
        return $this->actionId . 'Action';
    }
    
    public function getControllerAlias($controllerId = null, $moduleName = null, $version = null)
    {
        if($this->usingModule()) {
            return $this->getModule()->getControllerAlias($controllerId, $moduleName, $version);
        }
        return '@controller.' . $this->addVersionPath() . $this->getControllerClassName($controllerId);
    }

    public function usingModule()
    {
        return $this->getModuleId() ? true : false;
    }

	/**
     * @return the $_controllerId
     */
    public function _getControllerId()
    {
        return $this->controllerId;
    }

	/**
     * @param field_type $_controllerId
     */
    public function setControllerId($controllerId)
    {
        $this->controllerId = $controllerId;
    }

	/**
     * @return the $_actionId
     */
    public function getActionId()
    {
        return $this->actionId;
    }

	/**
     * @param field_type $_actionId
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;
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