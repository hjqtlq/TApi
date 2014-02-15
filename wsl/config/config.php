<?php
return array(
    'debug' => true,
    'log_leave' => 3,
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
    // 自定义配置，T::config->get('citys.cityname')
    'params' => array()
);