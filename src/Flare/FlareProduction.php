<?php

namespace Multipedidos\Flare;

use Spatie\FlareClient\Flare;

class Production
{
    public static function create()
    {
        return Flare::make(env('FLARE_KEY'))->registerFlareHandlers();
    }
}
