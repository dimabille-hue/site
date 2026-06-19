<?php

use App\Support\LegacySqlDump;
use Illuminate\Support\Facades\Artisan;

Artisan::command('legacy:import {--dry-run : Print legacy dump statistics without writing data}', function (): int {
    $dump = new LegacySqlDump(base_path('u2818473_agroinvest.sql'));
    $counts = $dump->rowCounts();

    $this->info('Legacy dump analysis');
    $this->line('Tables: '.count($counts));

    foreach (['pages', 'menus', 'blocks_data', 'gallery_lists', 'notice', 'stat'] as $table) {
        $this->line(sprintf('- %s: %d rows', $table, $counts[$table] ?? 0));
    }

    if (! $this->option('dry-run')) {
        $this->warn('Import writes are not enabled yet. Re-run with --dry-run while the mapping is finalized.');
    }

    return self::SUCCESS;
})->purpose('Inspect the legacy CMS SQL dump before importing data');
