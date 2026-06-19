<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('legacy:import --dry-run', function (): void {
    $this->info('Legacy import command placeholder. Implement data mapping after schema finalization.');
})->purpose('Import legacy CMS data from u2818473_agroinvest.sql');
