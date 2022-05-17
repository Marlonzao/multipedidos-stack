<?php 

namespace Multipedidos\Exception;

class BadToken extends \Exception
{
    public function __construct()
    {
        parent::__construct('Bad token', 401);
    }
}