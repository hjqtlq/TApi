<?php
// TODO FIX, just a demo
define('VERSION_STATUS_OK', 1);
define('VERSION_STATUS_UPGRADE', 2);
define('VERSION_STATUS_DENY', 3);
return array(
    // TODO FIX,之所以后面写成数组是为了方便扩展，最后需要确定是否需要写成数组
    // TODO FIX,或者可以直接去掉此文件，去读取是否有对应的配置文件，然后读取返回配置中的status，需要最终考虑如何实现
    '1.0.0' => array('status' => VERSION_STATUS_DENY),
    '1.2.0' => array('status' => VERSION_STATUS_UPGRADE),
    '1.3.0' => array('status' => VERSION_STATUS_UPGRADE),
    '1.4.0' => array('status' => VERSION_STATUS_OK),
    '2.0.0' => array('status' => VERSION_STATUS_OK)
);