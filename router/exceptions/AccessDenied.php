<?php
namespace fmihel\router\exceptions;

class AccessDenied extends \Exception
{
    public function __construct()
    {
        parent::__construct('access denied');
    }
}
