<?php namespace EvolutionCMS\Scraper;

use EvolutionCMS\Scraper\Console\Commands\MarkCompletedTasksAsCreated;
use EvolutionCMS\Scraper\Console\Commands\ProcessTask;
use EvolutionCMS\ServiceProvider;

class ScraperServiceProvider extends ServiceProvider
{
    protected $namespace = 'scraper';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->registerRoutingModule(
            'Scraper',
            __DIR__.'/../routes.php',
            'fa fa-copy'
        );
    }

    public function boot()
    {

        $this->commands([
            ProcessTask::class,
            //  Test::class,
            MarkCompletedTasksAsCreated::class,
        ]);
        $this->loadViewsFrom(__DIR__.'/../views', $this->namespace);
        $this->loadTranslationsFrom(__DIR__.'/../lang', $this->namespace);
        $this->loadMigrationsFrom(__DIR__.'/../resources/migrations');

        $this->publishes([
            __DIR__.'/../publishable/config' => EVO_CORE_PATH.'custom/config',
        ]);
    }
}
