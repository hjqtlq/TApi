<?php

/**
 *
 * @author TLQ
 *        
 */
abstract class TAbstractVc extends TBase
{
    abstract public function getRealVersion($params = array());
}