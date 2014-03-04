<?php
class TModule extends TBase
{
    private $_controllerAlias;
    private $moduleId;
    private $controllerId;
    private $actionId;
    private $_moduleAliases;
    private $_requestModuleId;
    private $_requestVersionString;
    private $application = null;
    
    public function init($scheduler = null)
    {
        $this->application = $scheduler;
        TApi::addImports($this->import());
        
        $this->moduleId = $scheduler->getModuleId();
        $this->controllerId = $scheduler->getControllerId();
        $this->actionId = $scheduler->getActionId();
        
//         $this->_requestControllerId = $scheduler->getControllerId();
//         $this->_requestModuleId = $scheduler->getModuleId();
//         $this->_requestVersionString = $scheduler->getVersion(true);
    }
    
    public function import()
    {
        return array();
    }
    
    // TODO 暂时不提供此方法，因为会有传值来源的问题，例如actionId从哪来，以及配置文件如何来
    private function getModuleAlias($moduleId = null, $version = null, $controllerId = null, $actionId = null)
    {
        $classCacheKey = $this->moduleId.'_'.$version;
        if(!isset($this->_moduleAliases[$classCacheKey])) {
            $realVersion = TApi::getRealVersion(
                $actionId, $controllerId, $moduleId, $version
            );
            $this->_moduleAliases[$classCacheKey] = '@module.' . $this->application->realVersionString . '.' . $this->application->getModuleId();
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
        return TApi::realPath($this->getModuleAlias($moduleName, $version));
    }
    
    public function getControllerAlias($controllerId = null, $moduleName = null, $version = null)
    {
        return $this->getModuleAlias($moduleName, $version) . '.'
             . $this->application->getControllerClassName($controllerId);
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