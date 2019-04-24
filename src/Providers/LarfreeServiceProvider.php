<?php

namespace Larfree\Providers;

use Larfree\Middleware\Authenticate;
use Larfree\Console\ModelMakeCommand;
use Larfree\Console\LarfreeMakeCommand;
use Illuminate\Support\ServiceProvider;
use Larfree\Console\RepositoryMakeCommand;
use Larfree\Console\ControllerMakeCommand;
use Larfree\Middleware\FormatResponse;

class LarfreeServiceProvider extends ServiceProvider
{
    protected $middlewareAliases = [
        'larfree.auth' => Authenticate::class,
    ];

    /**
     * @author iwulai
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->commands([
            'LarfreeMake' => LarfreeMakeCommand::class,
            'LarfreeModelMake' => ModelMakeCommand::class,
            'LarfreeRepositoryMake' => RepositoryMakeCommand::class,
            'LarfreeControllerMake' => ControllerMakeCommand::class,
        ]);

        $path = realpath(__DIR__ . '/../../config/config.php');

        $config = $this->app->make('path.config') . DIRECTORY_SEPARATOR . 'larfree.php';

        $this->publishes([$path => $config]);

        $this->mergeConfigFrom($path, 'larfree');
        /**
         * @var \Illuminate\Routing\Router $router
         */
        $router = $this->app['router'];

        $router->middleware(FormatResponse::class);

        $router->aliasMiddleware('larfree.auth', Authenticate::class);

        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
    }
}