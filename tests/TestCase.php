<?php

namespace Mvdnbrk\Documentation\Tests;

use Mvdnbrk\Documentation\Documentation;
use Mvdnbrk\Documentation\DocumentationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected Documentation $documentation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->documentation = app(Documentation::class);
    }

    /** @param  \Illuminate\Foundation\Application  $app */
    protected function getEnvironmentSetUp($app): void
    {
        $config = $app->get('config');

        $config->set('filesystems.disks.docs', [
            'driver' => 'local',
            'root' => __DIR__.'/stubs/docs',
        ]);

        $config->set('documentation.storage.disk', 'docs');

        $config->set('documentation.versions.published', [
            '1.0',
        ]);
    }

    /** @param  \Illuminate\Foundation\Application  $app */
    protected function getPackageProviders($app): array
    {
        return [
            DocumentationServiceProvider::class,
        ];
    }
}
