<?php

namespace Larfree\Console;

use Illuminate\Support\Arr;

class RepositoryMakeCommand extends MakeCommand
{
    protected $name = 'larfree:repository';

    protected $description = 'Create a new repository class extends Larfree\Support\Repository';

    protected $type = 'Repository';

    protected function buildClass($name)
    {
        $argumentName = $this->argument('name');

        $namespaceDummyRepository = str_replace('/', '\\', $argumentName) . 'Model';

        $dummyRepository = Arr::last(explode('/', $argumentName)) . 'Model';

        return str_replace(['NamespaceDummyModel', 'DummyModel'], [$namespaceDummyRepository, $dummyRepository], parent::buildClass($name));
    }
}