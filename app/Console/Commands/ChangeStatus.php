<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $Contracts = Contract::orderBy('id', 'DESC')->get();
        foreach ($Contracts as $Contract) {
            $combinedDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $Contract->delivery_date . ' ' . $Contract->delivery_time);
            $finalDateTime = $combinedDateTime->addDays($Contract->extension_days);
            if (Carbon::now()->greaterThan($finalDateTime)) {
                $car_details = Vehicle::orderBy('id', 'DESC')->get();
                foreach ($car_details as $car_detail) {
                    $car_detail->update(['status' => 'free']);
                }
            }
        }
    }
}
