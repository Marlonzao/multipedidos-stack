<?php

namespace Multipedidos\Flare;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    public function getDefaultDriver()
    {
        return 'local';
    }

    public function createProductionDriver()
    {
        return Production::create();
    }

    public function createLocalDriver()
    {
        return new Local();
    }
}