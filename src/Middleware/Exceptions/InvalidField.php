<?php 

namespace Multipedidos\Exception;

class InvalidField extends \Exception
{
    public function __construct($field)
    {
        parent::__construct($field, 400);
    }
}