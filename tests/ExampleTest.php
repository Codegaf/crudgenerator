<?php

namespace Codegaf\Crudgenerator\Tests;

use Codegaf\Crudgenerator\CrudgeneratorServiceProvider;
use Orchestra\Testbench\TestCase;

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
