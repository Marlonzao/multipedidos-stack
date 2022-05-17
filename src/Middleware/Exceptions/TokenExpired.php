<?php 

namespace Multipedidos\Exception;

class TokenExpired extends \Exception
{
    public function __construct()
    {
        parent::__construct('Provided token is expired', 401);
    }
}