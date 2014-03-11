<?php
/**
 * main.php
 *
 * 入口文件
 *
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 * @version 1.0.0
 * @since 1.0.0
 */
require './TApi/TApi.php';
define('API_ROOT', dirname(__FILE__));
define('API_NAME', 'wsl');
TApi::run();