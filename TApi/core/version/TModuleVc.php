<?php

/**
 *
 * @author TLQ
 *        
 */
class TModuleVc extends TAbstractVc
{
    private $moduleVersions = array();
    private $version;
    private $config;
    private $moduleId;
    private $controllerId;
    
    public function init($versionController = null)
    {
        $this->version = $versionController->getVersion();
        $this->config = $versionController->getConfig();
        $this->moduleId = TApi::getModuleId();
        $this->controllerId = $versionController->getControllerId();
    }
    
	/**
     * @see TIVersionController::createController()
     */
    public function getRealVersion($params = array())
    {
        $version = $this->getValue($params, 'version', $this->version);
        $moduleId = $this->getValue($params, 'moduleId', $this->moduleId);
        return $this->config[$version][$moduleId];
    }
    
//     public function _getModuleAlias($params = array())
//     {
//         return '@module.' . $this->getRealVersion($params);
//     }
    
//     public function getControllerAlias($params = array())
//     {
//         $this->getModuleAlias($params) . '.' . $this->getValue($params, 'controllerId', $this->controllerId);
//     }
}