<?php

/**
 *
 * @author TLQ
 *        
 */
class TException extends Exception
{
    protected $detail;
    
    public function __construct($message = null, $code = null, $detail = null)
    {
        $this->detail = $detail;
        parent::__construct($message, $code);
    }
    
    public function getError()
    {
        return array(
            'message' => $this->message,
            'code' => $this->code,
            'detail' => $this->detail
        );
    }
    
    public function getDetail()
    {
        return $this->detail;
    }
}