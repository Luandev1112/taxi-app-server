<?php

namespace App\Console\Commands;

use App\Models\Admin\Driver;
use Kreait\Firebase\Database;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class OfflineUnAvailableDrivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offline:drivers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Offline Un Available Drivers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Database $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current_timestamp = Carbon::now()->timestamp;
        $conditional_timestamp = Carbon::now()->subMinutes(15)->timestamp;

        $drivers = $this->database->getReference('drivers')->orderByChild('is_active')->equalTo(1)->getValue();

        foreach ($drivers as $key => $driver) {
            $driver_updated_at = Carbon::createFromTimestamp($driver['updated_at']);
            if ($conditional_timestamp > $driver_updated_at->timestamp) {
                $updatable_offline_date_time = Carbon::createFromTimestamp($driver_updated_at->timestamp);
                $mysql_driver = Driver::where('id', $driver['id'])->first();

                // Get last online record
                if ($mysql_driver && $mysql_driver->driverAvailabilities()) {
                    $availability = $mysql_driver->driverAvailabilities()->where('is_online', true)->orderBy('online_at', 'desc')->first();
                    $created_params['duration'] = 0;
                    if ($availability) {
                        $last_online_date_time = Carbon::parse($availability->online_at);
                        $last_offline_date = Carbon::parse($updatable_offline_date_time);
                        $duration = $last_offline_date->diffInMinutes($last_online_date_time);
                        $created_params['duration'] = $availability->duration+$duration;
                    }
                    $created_params['is_online'] = false;
                    $created_params['online_at'] = $updatable_offline_date_time;
                    $mysql_driver->driverAvailabilities()->create($created_params);

                    $mysql_driver->active = 0;
                    $mysql_driver->save();
                }
                
                
            }
        }

        $this->info("success");
    }
}
