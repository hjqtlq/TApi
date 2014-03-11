<?php
define('DS', DIRECTORY_SEPARATOR);
defined('TAPI_ROOT') or define('TAPI_ROOT', dirname(__FILE__) . DS);
/**
 * TApi.php
 *
 * TApi入口文件
 *
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TApi
 *
 * TApi入口类，所有请求需要通过此类来创建
 * 这是一个静态类，目的是为了方便调用，并且提供一下常用方法的代理
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class TApi
{
    private static $_request;
    private static $_version;
    private static $_versionController;
    private static $_imports = array();
    private static $_application = null;
    private static $_controller = null;
    private static $_config = array();
    
    public static function run()
    {
        // TODO 需要优化这里的常量定义
        $confg = require_once API_ROOT . DIRECTORY_SEPARATOR . API_NAME. '/config/config.php';
        self::$_config = new TConfig($confg);
        self::$_versionController = TApi::createClass('TVersionController');
        self::getApp()->run();
    }

    public static function getConfig()
    {
        return self::$_config;
    }
    
    public static function getApp()
    {
//         return new TScheduler();
        if(empty(self::$_application)) {
            self::$_application = self::createClass('TApplication');
        }
        return self::$_application;
    }
    
    public static function getVersion($toString = false, $version = null)
    {
        return self::getApp()->getVersionController()->getVersion($toString, $version = null);
    }
    
    public static function getRealVersion($actionId = null, $controllerId = null, $moduleId = null, $version = null)
    {
        return self::getApp()->getVersionController()->getRealVersion($actionId, $controllerId, $moduleId, $version);
    }
    
    public static function getModule()
    {
        return self::getApp()->getModule();
    }
    
    public static function getModuleId($moduleName = null)
    {
        return self::getApp()->getModuleId($moduleName);
    }
    
    public static function getModuleAlias($moduleName = null, $version = null)
    {
        return self::getApp()->getModuleAlias($moduleName = null, $version = null);
    }
    
    public static function getControllerId($controllerId = null)
    {
        return self::getApp()->getControllerId();
    }
    
    public static function getControllerClassName($controllerId = null)
    {
        return self::getApp()->getControllerClassName($controllerId = null);
    }
    
    public static function getActionId($actionId = null)
    {
        return self::getApp()->getActionId($actionId = null);
    }
    
    public static function getControllerAlias($controllerId = null, $moduleName = null, $version = null)
    {
        return self::getApp()->getControllerAlias($controllerId = null, $moduleName = null, $version = null);
    }
    
    public static function getRequest($key = null)
    {
        return self::getApp()->getRequest($key);
    }
    
    public static $_classes = array();
    
    public static function createClass($alias, $params = array())
    {
        if(isset(self::$_classes[$alias])) {
            return self::$_classes[$alias];
        }
        $object = null;
        $className = self::import($alias);
        if(false !== $className) {
            self::$_classes[$alias] = new $className();
            if(method_exists(self::$_classes[$alias], 'init')) {
//             if(self::$_classes[$Alias] instanceof TBase) {
                self::$_classes[$alias]->init($params);
            }
        }
        return self::$_classes[$alias];
    }
    
    public static function import($aliases)
    {
        // TODO fix，需要可以支持引入目录（*）
        // TODO FIX，需要可以支持支持夸版本引用
        // TODO FIX，需要可以方便的引用模块里面的内容，不需要版本号
        $className = $aliases;
        if(isset(self::$_imports[$aliases]) && (class_exists($aliases) || interface_exists($aliases))) {
            return self::$_imports[$aliases] = $aliases;
        }
        
        $importPath = str_replace('.', DIRECTORY_SEPARATOR, $aliases);
        if(0 === strpos($aliases, '@')) {
            $path = self::realPath($aliases);
            $className = basename($path, '.php');
            self::$_imports[$className] = $path;
            require self::$_imports[$className];
        } else {
            $path = self::autoload($className);
            if(false === $path) {
                throw new TException($className);
            }
            self::$_imports[$className] = $path;
        }
        return $className;
    }
    
    public static function addImports($aliases)
    {
        foreach($aliases as $key => $alias) {
            if(is_array($alias) && !empty($alias)) {
                array_push(self::$_imports, $alias);
            } else {
                self::$_imports[strval($key)] = strval($alias);
            }
        }
    }
    
    public static function realPath($alias, $ext = '.php')
    {
        $importPath = str_replace('.', DIRECTORY_SEPARATOR, $alias);
        return $path = API_ROOT . DIRECTORY_SEPARATOR . API_NAME . DIRECTORY_SEPARATOR . ltrim($importPath, '@') . $ext;
    }
    
    public static function autoload($className)
    {
        if(isset(self::$_corePaths[$className])) {
            $path = TAPI_ROOT . self::$_corePaths[$className];
            require $path;
            return $path;
        }
        return false;
    }
    
    private static $_corePaths = array(
        'T' => 'T.php',
        'TBase' => 'core/TBase.php',
        'TController' => 'core/TController.php',
        'TVersionController' => 'core/TVersionController.php',
        'TModel' => 'core/TModel.php',
        'TModule' => 'core/TModule.php',
        'TRequest' => 'core/TRequest.php',
        'TResponse' => 'core/TResponse.php',
        'TApplication' => 'core/TApplication.php',
        'TAbstractVc' => 'core/version/TAbstractVc.php',
        'TModuleVc' => 'core/version/TModuleVc.php',
        'TException' => 'exceptions/TException.php',
        'TRequestException' => 'exceptions/TRequestException.php',
        'TResponseException' => 'exceptions/TResponseException.php',
        'TIRequest' => 'interfaces/TIRequest.php',
        'TIResponse' => 'interfaces/TIResponse.php',
        'TConfig' => 'core/TConfig.php',
    );
}
spl_autoload_register(array('TApi', 'autoload'));