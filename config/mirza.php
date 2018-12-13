<?php
/**
 * Mirza Yandex Translator Configuration File
 * 
 * Tutorial:
 * 1. Publish this file by executing `php artisan vendor:publish --provider:"yak0d3/Mirza_Yandex_Translator/MirzaServiceProvider"`
 * 2. Add `YANDEX_KEY` variable to your .env file and set it to your own Yandex.Translate API Key
 * 
 */

 
return [
    'secret' => env('YANDEX_KEY'),
];