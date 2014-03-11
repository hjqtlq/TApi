<?php
/**
 * TResponse.php
 *
 * Response文件
 *
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TResponse
 *
 * 处理返回值，提供几种标准格式的输出
 * FIXME 需要实现一个储存器，并且在这里持有一个储存器的实例，用储存器来储存返回数据
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class TResponse extends TBase
{
    public static $data = array();
    
    public static function send()
    {
        echo json_encode(self::$data);
    }
}