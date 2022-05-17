<?php 

namespace Multipedidos\Exception;

class InvalidCredentials extends \Exception
{
    public function __construct()
    {
        parent::__construct('Invalid Credentials', 400);
    }
}