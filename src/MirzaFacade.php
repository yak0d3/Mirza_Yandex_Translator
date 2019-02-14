<?php

namespace yak0d3\Mirza;

use Illuminate\Support\Facades\Facade;

class MirzaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Mirza';
    }
}
