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
         * 先从1开始做，TVersionController需要采用代理模式来实现
         * 
         * 1-module级别
         *  - 仅仅支持版本复制，每新建一个版本都是前一个版本的copy或者完全指向前一个版本
         * 2-controller级别
         *  - 提供对controller级别的版本控制，controller可以指向任意一个版本
         * 3-action级别
         *  - 提供对最终action级别的版本控制，action可以指向任意版本
         */
        'mode' => 1,
        'versionVar' => 'v',
    ),
    'application' => array(
        'defaultAction' => 'index',
        'defaultController' => 'site',
        'actionVar' => 'a',
        'controllerVar' => 'c',
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