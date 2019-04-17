<?php

namespace Larfree\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class LarfreeMakeCommand extends Command
{
    protected $name = 'larfree:make';

    protected $description = 'Execute larfree:controller, larfree:model, larfree:repository';

    public function handle()
    {
        $name = $this->argument('name');

        $namespace = $this->argument('namespace');

        $this->call('larfree:controller', ['name' => $name, 'namespace' => $namespace]);

        $this->call('larfree:model', ['name' => $name]);

        $this->call('larfree:repository', ['name' => $name]);
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of classes'],
            ['namespace', InputArgument::OPTIONAL, 'The namespace of the controller'],
        ];
    }
}