<?php
class TModule extends TBase
{
    public $controllerAlias;
    public $moduleId;
    public $controllerId;
    public $actionId;
    public $moduleAlias;
    
    
    private $route = null;
    /**
     * 初始化参数
     * 
     * 引入使用module的时候所必须的文件@see TApi::addImports($this->import());
     * 初始化module ID
     * 初始化controller ID
     * 初始化action ID
     * 
     * @see TBase::init()
     */
    public function init($route = null)
    {
        $this->route = $route;
        TApi::addImports($this->import());
        
        $this->moduleAlias = '@module.' . $this->route->realVersionString . '.' . $this->route->getModuleId();
        $this->controllerAlias = $this->moduleAlias . '.' . $this->route->getControllerClassName();
        
        $this->moduleId = $route->getModuleId();
        $this->controllerId = $route->getControllerId();
        $this->actionId = $route->getActionId();
    }
    
    public function import()
    {
        return array();
    }
    
    private function getModuleAlias()
    {
        return $this->moduleAlias;
    }
    
    // TODO 暂时不提供此方法，因为会有传值来源的问题，例如actionId从哪来，以及配置文件如何来
    private function createModuleAlias($moduleId = null, $version = null, $controllerId = null, $actionId = null)
    {
        $classCacheKey = $this->moduleId.'_'.$version;
        if(!isset($this->_moduleAliases[$classCacheKey])) {
            $realVersion = TApi::getRealVersion(
                $actionId, $controllerId, $moduleId, $version
            );
            $this->_moduleAliases[$classCacheKey] = '@module.' . $this->route->realVersionString . '.' . $this->route->getModuleId();
        }
        return $this->_moduleAliases[$classCacheKey];    
    }
    
    public function hasModule($moduleName, $versionString = null)
    {
        // TODO CHECK, 需要确认在超大并发的时候，is_dir会不会提升对IO的负担
        return is_dir(TApi::realPath($this->getModuleAlias($moduleName, $versionString), ''));
    }
    
    public function getModulePath($moduleName = null, $version = null)
    {
        return TApi::realPath($this->getModuleAlias());
    }
    
    public function getControllerAlias()
    {
        return $this->controllerAlias;
    }
    
    public function getControllerPath()
    {
        
    }
    
    public function getController()
    {
        
    }
    
    public function _addVersionPath($version = null)
    {
    }
}