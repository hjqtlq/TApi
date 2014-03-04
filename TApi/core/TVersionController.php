<?php
/**
 * 框架的核心咯
 * 哇咔咔
 * 需要支持多种version调度模式，并且可以随意切换
 * @author TLQ
 * 
 */
class TVersionController extends TBase
{
    private $_requestVersion = null;
    private $_versionConfig = array();
    
    public function init($version = null)
    {
        $this->setVersion($version);
        $this->_requestVersion = $_REQUEST[TApi::getConfig()->versionControl->versionVar];
        // TODO require version config file
        $requestVersionConfig = require API_ROOT . '/' . API_NAME . '/config/versions/' . $this->_requestVersion . '.php';
        $this->_versionConfig[$this->_requestVersion] = $requestVersionConfig;
    }
    
    public function getConfig()
    {
        return $this->_versionConfig;
    }

    /**
     * 得到真实的版本号
     */
    public function getRealVersion($params = array())
    {
        /*
         * TODO FIX 需要用工厂建立
         */
        $realVersion = TApi::createClass('TModuleVc', $this)->getRealVersion($params);
        $toString = $this->getValue($params, 'toString', false);
        if($toString) {
            $realVersion = $this->getVersion($toString, $realVersion);
            
        }
        return $realVersion;
    }
    
    public function getVersion($toString = false, $version = null)
    {
        $version = is_null($version) ? $this->_requestVersion : $version;
        if($toString) {
            $version = str_replace('.', '_', $version);
        }
        return $version;
    }
}