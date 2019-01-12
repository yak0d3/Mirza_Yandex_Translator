<?php

namespace yak0d3\mirza_yandex_translator;

use Illuminate\Support\Facades\Facade;

class MirzaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Mirza';
    }
}
