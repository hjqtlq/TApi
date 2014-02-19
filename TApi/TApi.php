<?php
defined('TAPI_ROOT') or define('TAPI_ROOT', dirname(__FILE__));
class TApi
{
    private static $_api;
    private static $_routing;
    private static $_imports = array();
    
    public static function run()
    {
        self::init();
        $route = self::$_routing;
        $moduleName = $route->moduleName;
        $controllerName = $route->controllerName;
        $actionName = $route->actionName;
    }
    
    public static function init()
    {
        self::$_routing = new TRouting();
    }
    
    public static function createClass($aliase, $params = array())
    {
        // TODO DEBUG import class file, test ReflectionClass>newInstanceArgs()
        $object = null;
        $className = $aliase;
        self::import($aliase);
        if(!empty($params)) {
            $reflection  = new ReflectionClass($className);
            $object = $reflection->newInstanceArgs($params);
        } else {
            $object = new $className();
        }
        return $object;
    }
    
    public static function import($aliase)
    {
        if(isset(self::$_imports[$aliase])) {
            return self::$_imports[$aliase];
        }
        if(class_exists($aliase, false) || interface_exists($aliase, false)) {
            return self::$_imports[$aliase] = $aliase;
        }
        
        $importPath = str_replace('.', DIRECTORY_SEPARATOR, $aliase);
        if(0 === strpos('@', $aliase)) {
            self::$_imports[$aliase] = API_ROOT . DIRECTORY_SEPARATOR . API_NAME . DIRECTORY_SEPARATOR . ltrim('@', $importPath) . '.php';
        } else {
            throw new TException('import error', 00001);
        }
        require self::$_imports[$aliase];
        return self::$_imports[$aliase];
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