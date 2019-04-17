<?php

namespace Larfree\Providers;

use Larfree\Console\ModelMakeCommand;
use Larfree\Console\LarfreeMakeCommand;
use Illuminate\Support\ServiceProvider;
use Larfree\Console\RepositoryMakeCommand;
use Larfree\Console\ControllerMakeCommand;

class LarfreeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            'LarfreeMake' => LarfreeMakeCommand::class,
            'LarfreeModelMake' => ModelMakeCommand::class,
            'LarfreeRepositoryMake' => RepositoryMakeCommand::class,
            'LarfreeControllerMake' => ControllerMakeCommand::class,
        ]);
    }
}