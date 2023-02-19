<?php

namespace Haziqzahari\Laraveldatatable\Console;


use Illuminate\Console\GeneratorCommand;

class CreateDataTable extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected function getStub()
    {
        return base_path('stubs/action.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\DataTable\Console';
    }

    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        // Do string replacement
        return str_replace('DummyClass', $class, $stub);
    }
}
