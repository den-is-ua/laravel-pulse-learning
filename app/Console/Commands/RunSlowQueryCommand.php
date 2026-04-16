<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('run:slow_query')]
#[Description('Run a deliberately slow MySQL query (SELECT SLEEP(2))')]
class RunSlowQueryCommand extends Command
{
    public function handle(): int
    {
        DB::select('SELECT SLEEP(2)');

        $this->components->info('Slow query finished.');

        return self::SUCCESS;
    }
}
