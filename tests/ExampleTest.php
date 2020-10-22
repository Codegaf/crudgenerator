<?php

namespace Codegaf\Crudgenerator\Tests;

use Orchestra\Testbench\TestCase;
use Codegaf\Crudgenerator\CrudgeneratorServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [CrudgeneratorServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
