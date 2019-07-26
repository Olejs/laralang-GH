<?php
namespace Thomasvvugt\LaraLang;

use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerLoader();

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });

        // Merge configuration files
        $this->mergeConfigFrom(__DIR__.'/../config/laralang.php', 'laralang');

        // Set the LaraLang Manager
        $this->app->singleton('laralang-manager', function ($app) {
            $manager = $app->make('Thomasvvugt\LaraLang\Manager');
            return $manager;
        });

        // Set console commands
        $this->app->singleton('command.laralang.import', function ($app) {
            return new Console\ImportCommand($app['laralang-manager']);
        });
        $this->commands('command.laralang.import');
    }
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laralang.php' => config_path('laralang.php'),
            ], 'config');
            if (! class_exists('CreateLaraLangSetupTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../database/migrations/create_laralang_setup_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_laralang_setup_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Register the translation line loader. This method registers a
     * `TranslationLoaderManager` instead of a simple `FileLoader` as the
     * applications `translation.loader` instance.
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new TranslationLoaderManager($app['files'], $app['path.lang']);
        });
    }

    public function provides()
    {
        return ['translator', 'translation.loader', 'laralang', 'command.laralang.import'];
    }
}