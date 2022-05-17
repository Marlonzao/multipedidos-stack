<?php 

namespace Multipedidos\Exception;

class TokenNotProvided extends \Exception
{
    public function __construct()
    {
        parent::__construct('Token not provided', 400);
    }
}