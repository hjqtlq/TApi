<?php
/**
 *
 * @author TLQ
 *        
 */
class TResponse extends TBase
{
    public static $data = array();
    
    public static function send()
    {
        echo json_encode(self::$data);
    }
}