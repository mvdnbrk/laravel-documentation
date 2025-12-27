<?php

declare(strict_types=1);

namespace Mvdnbrk\Documentation\Tests\Console;

use Mvdnbrk\Documentation\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InstallCommandTest extends TestCase
{
    #[Test]
    public function it_can_install_the_package(): void
    {
        $this->artisan('documentation:install')
            ->expectsOutput('Publishing Documentation Configuration...')
            ->expectsOutput('Documentation installed successfully.')
            ->assertExitCode(0);
    }
}
