<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTelemetryUpdate;
use App\Models\Vehicle;
use Illuminate\Console\Command;

class UpdateTelemetry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telemetry:update
                            {--vehicle= : Update specific vehicle ID}
                            {--active-only : Only update vehicles in active reservations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update simulated telemetry data for vehicles (battery, location, distance)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting telemetry update...');

        $query = Vehicle::query();

        // Filter by specific vehicle if provided
        if ($this->option('vehicle')) {
            $query->where('id', $this->option('vehicle'));
        }

        // Filter only active vehicles if specified
        if ($this->option('active-only')) {
            $query->where('status', 'reserved');
        }

        $vehicles = $query->get();

        if ($vehicles->isEmpty()) {
            $this->warn('No vehicles found to update.');
            return 0;
        }

        $this->info("Found {$vehicles->count()} vehicle(s) to update.");

        $bar = $this->output->createProgressBar($vehicles->count());
        $bar->start();

        foreach ($vehicles as $vehicle) {
            // Dispatch job to process telemetry update
            ProcessTelemetryUpdate::dispatch($vehicle);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Telemetry update jobs dispatched successfully!');

        return 0;
    }
}
