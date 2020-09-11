<?php

namespace Mvdnbrk\Documentation\Tests\Console;

use Mvdnbrk\Documentation\Tests\TestCase;

class InstallCommandTest extends TestCase
{
    /** @test */
    public function it_can_install_the_package()
    {
        $this->artisan('documentation:install')
            ->expectsOutput('Publishing Documentation Configuration...')
            ->expectsOutput('Documentation installed successfully.')
            ->assertExitCode(0);
    }
}
