<?php

declare(strict_types=1);

namespace Hetbo\Solade;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class SoladeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('solade', []);

            $factory->add('solade', array_merge(['path' => __DIR__.'/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/solade.php', 'solade');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/svg' => public_path('hetbo/solade'),
            ], 'solade');

            $this->publishes([
                __DIR__ . '/../config/solade.php' => $this->app->configPath('solade.php'),
            ], 'solade-config');
        }
    }
}
