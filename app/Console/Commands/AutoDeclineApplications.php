<?php
namespace App\Console\Commands;

use App\Models\Application;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AutoDeclineApplications extends Command {
    protected $signature   = 'applications:auto-decline';
    protected $description = 'Automatically decline applications that have been approved for more than 2 days without being marked ready to pick up';

    public function handle() {
        $twoDaysAgo = Carbon::now()->subDays(2);

        $expired = Application::where('status', 'approved')
            ->where('created_at', '<=', $twoDaysAgo)
            ->get();

        foreach ($expired as $application) {
            $application->update(['status' => 'declined']);
        }

        $this->info('Auto-declined ' . $expired->count() . ' applications.');
    }
}

class AutoMissedApplications extends Command {
    protected $signature   = 'applications:auto-missed';
    protected $description = 'Automatically mark applications as missed if they have been ready to pick up for 2 days without being picked up';

    public function handle() {
        $twoDayAgo = Carbon::now()->subDays(2);

        $missed = Application::where('status', 'ready_to_pickup')
            ->where('updated_at', '<=', $twoDayAgo)
            ->get();

        foreach ($missed as $application) {
            $application->update(['status' => 'missed']);
        }

        $this->info('Auto-marked ' . $missed->count() . ' applications as missed.');
    }
}