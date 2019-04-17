<?php

namespace Larfree\Console;

use Illuminate\Support\Str;

class ModelMakeCommand extends MakeCommand
{
    protected $name = 'larfree:model';

    protected $description = 'Create a new Eloquent model class extends Larfree\Support\Model';

    protected $type = 'Model';

    protected function buildClass($name)
    {
        $tableName = Str::plural(Str::snake(str_replace('/', null, $this->argument('name'))));

        return str_replace('DummyTableName', $tableName, parent::buildClass($name));
    }
}