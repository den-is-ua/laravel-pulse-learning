<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

#[Signature('new:cache {key} {value}')]
#[Description('Store a key-value pair in the application cache')]
class NewCacheCommand extends Command
{
    public function handle(): int
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        Cache::put($key, $value);
        Cache::get($key);
        
        $this->components->info("Cached [{$key}] => {$value}");

        return self::SUCCESS;
    }
}
