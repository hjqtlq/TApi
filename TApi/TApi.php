<?php
defined('TAPI_ROOT') or define('TAPI_ROOT', dirname(__FILE__));
class TApi
{
    public $config;
    public $version;
    public $module = 'TModule';
    public $controller = 'TController';
    public $model = 'TMoodel';
    private static $_api;
    private static $_imports = array();
    
    public static function run()
    {
        // TODO run api
    }
    
    public static function init()
    {
        
    }
    
    public static function import($path)
    {
        if(isset(self::$_imports[$path])) {
            return self::$_imports[$path];
        }
        if(class_exists($path, false) || interface_exists($path, false)) {
            return self::$_imports[$path] = $path;
        }
        
        $importPath = str_replace('.', DIRECTORY_SEPARATOR, $path);
        if(0 === strpos('@', $path)) {
            self::$_imports[$path] = API_ROOT . DIRECTORY_SEPARATOR . API_NAME . DIRECTORY_SEPARATOR . ltrim('@', $importPath) . '.php';
        } else {
            throw new TException('import error', 00001);
        }
        require self::$_imports[$path];
        return self::$_imports[$path];
    }
    
    public static function autoload($className)
    {
        if(isset(self::$_corePaths[$className])) {
            require TAPI_ROOT . self::$_corePaths[$className];
        }
    }
    
    private static $_corePaths = array(
        'TBase' => '/core/TBase.php',
        'TController' => '/core/TController.php',
        'TModel' => '/core/TModel.php',
        'TModule' => '/core/TModule.php',
        'TRequest' => '/core/TRequest.php',
        'TResponse' => '/core/TResponse.php',
        'TRouting' => '/core/TRouting.php',
    );
}
spl_autoload_register(array('TApi', 'autoload'));