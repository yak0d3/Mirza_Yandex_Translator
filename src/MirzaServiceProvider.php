<?php

namespace yak0d3\Mirza;

use Blade;
use Illuminate\Support\ServiceProvider;

class MirzaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mirza.php' => config_path('mirza.php'),
        ]);
        Blade::directive('translate', function ($expression) {
            $expression = explode(',', $expression);
            $text = $expression[0];
            $lang = $expression[1];

            return "<?php echo Mirza::translate($text, $lang); ?>";
        });
        Blade::directive('langselect', function () {
            return '<?php echo Mirza::languages_select(); ?>';
        });
        Blade::directive('yandex_rights', function ($expression) {
            $expression = explode(',', $expression);
            $color = $expression[0];
            $fontsize = $expression[1];

            return "<?php echo Mirza::yandex_rights($color,$fontsize); ?>";
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/mirza.php', 'mirza'
        );

        \App::singleton('MirzaClient', function () {
            return new MirzaClient(config('mirza.secret'));
        });
        \App::bind('Mirza', function () {
            $client = resolve('MirzaClient');

            return new Mirza($client);
        });

        \App::alias('Mirza', MirzaFacade::class);
    }
}
