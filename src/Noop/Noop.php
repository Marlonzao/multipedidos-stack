<?php

namespace Multipedidos;

class Noop
{
    public function __call($method, $args)
    {
        return;
    }

    public static function __callStatic($method, $args)
    {
        return;
    }
}
