<?php
/**
 * TRequest.php
 *
 * Request文件
 *
 * @author T.L.Q. <hjq_tlq@163.com>
 * @link http://www.tapi.com.cn/
 * @copyright 2014 TApi team
 * @license http://www.tapi.com.cn/license/
 */

/**
 * TRequest
 *
 * 请求参数处理类，不赞同直接用全局变量$_POST等获取参数
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class TRequest extends TBase
{
    public $data = array();
    
    public function init($params = array())
    {
        $this->data = $params;
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }
    
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}