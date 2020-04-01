<?php

namespace Mvdnbrk\Documentation\Tests;

use Mvdnbrk\Documentation\DocumentationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        //
    }

    /** @param  \Illuminate\Foundation\Application  $app */
    protected function getEnvironmentSetUp($app): void
    {
        //
    }

    /** @param  \Illuminate\Foundation\Application  $app */
    protected function getPackageProviders($app): array
    {
        return [
            DocumentationServiceProvider::class,
        ];
    }
}
