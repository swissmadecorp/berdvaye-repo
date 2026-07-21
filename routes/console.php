<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\VisitorTrackingService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('visitor-monitor:purge', function (VisitorTrackingService $trackingService) {
    $purged = $trackingService->purgeExpiredData();

    $this->info(sprintf(
        'Deleted %d visitor sessions and %d visitor profiles older than %d days.',
        $purged['sessions_deleted'],
        $purged['profiles_deleted'],
        $purged['retention_days'],
    ));
})->purpose('Delete visitor monitor records older than the configured retention window');
