<?php
/**
 * TVersionController.php
 *
 * 版本控制器文件
 *
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TVersionController
 *
 * 版本控制器类，用于通过路由获取真实的文件版本，此类仅仅只希望是一个代理者，具体实现
 * 需要具体的供应商提供，通过配置文件来指定使用哪一个版本控制提供者的实例
 * 需要适用代理模式，实现多种版本控制方式。
 *
 * @version 1.0.0
 * @since 1.0.0
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
     * @param $params 参数
     * @return string 真实的版本号
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