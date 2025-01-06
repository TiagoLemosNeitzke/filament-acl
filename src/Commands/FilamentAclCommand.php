<?php

namespace TiagoLemosNeitzke/FilamentAcl\FilamentAcl\Commands;

use Illuminate\Console\Command;

class FilamentAclCommand extends Command
{
    public $signature = 'filamentacl';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
