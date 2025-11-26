<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AutoOffline extends Command
{
    protected $signature = 'user:auto-offline';
    protected $description = 'Set user offline jika idle > 2 menit';

    public function handle()
    {
       User::whereNotNull('last_seen')
        ->where('last_seen', '<', now()->subMinutes(2))
        ->update(['status' => 'nonaktif']);

        $this->info('Idle users updated to offline.');
    }
}
