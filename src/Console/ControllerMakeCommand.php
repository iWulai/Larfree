<?php

namespace Larfree\Console;

use Illuminate\Support\Arr;
use Symfony\Component\Console\Input\InputArgument;

class ControllerMakeCommand extends MakeCommand
{
    protected $name = 'larfree:controller';

    protected $description = 'Create a new controller class extends Larfree\Support\Controller';

    protected $type = 'Controller';

    protected $namespace = 'Http';

    protected function buildClass($name)
    {
        $argumentName = $this->argument('name');

        $namespaceDummyRepository = str_replace('/', '\\', $argumentName) . 'Repository';

        $dummyRepository = Arr::last(explode('/', $argumentName)) . 'Repository';

        return str_replace(['NamespaceDummyRepository', 'DummyRepository'], [$namespaceDummyRepository, $dummyRepository], parent::buildClass($name));
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['namespace', InputArgument::OPTIONAL, 'The namespace of the class'],
        ];
    }
}