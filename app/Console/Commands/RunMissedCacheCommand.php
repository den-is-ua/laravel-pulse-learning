<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('run:missed_cache')]
#[Description('Perform a cache lookup that misses (key does not exist)')]
class RunMissedCacheCommand extends Command
{
    public function handle(): int
    {
        $key = 'missed_cache:'.uniqid('', true);

        Cache::get($key);

        $this->components->info("Cache miss recorded for key [{$key}].");

        return self::SUCCESS;
    }
}
