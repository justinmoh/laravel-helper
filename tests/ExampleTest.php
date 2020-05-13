<?php

namespace Justinmoh\LaravelHelper\Tests;

use Orchestra\Testbench\TestCase;
use Justinmoh\LaravelHelper\LaravelHelperServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelHelperServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
