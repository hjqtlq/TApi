<?php
/**
 *
 * @author TLQ
 *        
 */
class TApplication extends TBase
{
    public $defaultActionId = 'index';
    public $defaultControllerId = 'site';
    public $actionVar = 'a';
    public $controllerVar = 'c';
    public $moduleVar = 'm';
    public $versionVar = 'v';
    
    public $versionController;
    
    public $data = array();
    
    public $module;
    public $moduleId;
//     public static $moduleName;
    public $moduleAlias;
    
    public $controller;
    public $controllerId;
    public $controllerClassName;
    public $controllerAlias;
    
    public $actionId;
    public $actionMethodName;
    
    public $version;
    public $realVersionString;
    public $request;
    
    public function run()
    {
        //TODO FIX 这里需要些版本验证逻辑
        if(true !== $this->praseRequest()) {
            throw new TRequestException();
        }
        $this->createController()->run();
    }
    
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
    
    public function getRequest($key = null)
    {
        return $this->request->{$key};
    }
    
    public function getVersionController()
    {
        if(empty($this->versionController)) {
            $this->versionController = TApi::createClass('TVersionController');
        }
        return $this->versionController;
    }
    
    public function createController()
    {
        return TApi::createClass($this->getControllerAlias(), $this);
    }
    
    public function init($params = null)
    {
        $this->data = $_REQUEST;
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
    
    private function _getData($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
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
        return is_null($controllerId) ? $this->data[$this->controllerVar] : $controllerId;
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
    
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
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