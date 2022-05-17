<?php 

namespace Multipedidos\Exception;

class TokenTooOld extends \Exception
{
    public function __construct()
    {
        parent::__construct('Provided token is too old', 400);
    }
}