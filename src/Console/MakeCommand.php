<?php

namespace Larfree\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

abstract class MakeCommand extends GeneratorCommand
{
    protected $namespace;

    protected function getNameInput()
    {
        return trim($this->argument('name') . $this->type);
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->namespace)
        {
            $rootNamespace  = $rootNamespace . '\\' . $this->namespace;
        }

        if ($this->type)
        {
            $rootNamespace  = $rootNamespace . '\\' . Str::plural($this->type);
        }

        if ($this->hasArgument('namespace') && $namespace = $this->argument('namespace'))
        {
            $rootNamespace  = $rootNamespace . '\\' . $namespace;
        }

        return $rootNamespace;
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/' . $this->type . '.stub';
    }
}