<?php 

namespace Multipedidos\Exception;

class InsuficientPermissions extends \Exception
{
    public function __construct()
    {
        parent::__construct('User does not have permissions', 403);
    }
}