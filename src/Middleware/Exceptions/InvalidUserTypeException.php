<?php 

namespace Multipedidos\Exception;

class InvalidUserType extends \Exception
{
    public function __construct()
    {
        parent::__construct('Invalid user type', 400);
    }
}