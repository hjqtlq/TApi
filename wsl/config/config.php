<?php
return array(
    'debug' => true,
    'logLeave' => 3,
    'module' => array(
        'enable' => true,
        'moduleVar' => 'm',
    ),
    'versionControl' => array(
        'enable' => true,
        /*
         * 版本控制模式
         * 
         * 0 all级别
         *  - 版本升级粒度为全部。新版本都是前一个版本的升级版本，版本内包含完整的功能实现。没有升级
         *    的也会被全部原因复制到新版本中。
         * 
         * 1 module级别
         *  - 新版本升级粒度为module。当module有任意变化就需要复制完整的module
         *    到新的版本，没有升级的module不需要复制。例如：
         *      版本从1.0.0升级到1.1.0，其中模块user中有文件修改，而其他模块都没有
         *      更改，那么在1.1.0中，只会包括user模块中的所有文件
         * 
         * 2 controller级别
         *  - 版本升级的粒度为controller。提供对controller级别的版本控制，controller可以指向任意一个版本。
         * 3 action级别
         *  - 提供对最终action级别的版本控制，action可以指向任意版本。
         */
        'controlLeave' => 1,
        'defaultVersion' => '1.0.0',
    ),
    'application' => array(
//         'defaultAction' => 'index',
//         'defaultController' => 'site',
//         'actionVar' => 'a',
//         'controllerVar' => 'c',
    ),
    'route' => array(
        'defaultAction' => 'index',
        'defaultController' => 'site',
        'actionVar' => 'a',
        'controllerVar' => 'c',
        'versionVar' => 'v',
    ),
    'db' => array(
        'drive' => 'mysql',
        'username' => 'root',
        'password' => '',
        'dbName' => 'TApi',
        'charset' => 'utf8',
        'tablePerfix' => 'tapi_',
    ),
    'cache' => array(
        'drive' => 'memcache',
    ),
    // 版本控制配置

    // 自定义配置，T::config->get('citys.cityname')
    'params' => array()
);