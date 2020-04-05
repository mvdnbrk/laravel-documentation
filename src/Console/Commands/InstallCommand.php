<?php

namespace Mvdnbrk\Documentation\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'documentation:install';

    protected $description = 'Install the documentation';

    public function handle()
    {
        $this->comment('Publishing Documentation Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'documentation-config']);

        $this->info('Documentation installed successfully.');
    }
}
