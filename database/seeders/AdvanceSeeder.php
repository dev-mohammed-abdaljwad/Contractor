<?php

namespace Database\Seeders;

use App\Models\Advance;
use App\Models\Worker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdvanceSeeder extends Seeder
{
    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            $workers = Worker::where('contractor_id', $contractor->id)->limit(5)->get();

            foreach ($workers as $worker) {
                // Create 1-2 advances per worker
                for ($i = 0; $i < rand(1, 2); $i++) {
                    Advance::create([
                        'contractor_id' => $contractor->id,
                        'worker_id' => $worker->id,
                        'amount' => rand(500, 2000),
                        'advance_date' => Carbon::today()->subDays(rand(1, 10)),
                        'notes' => 'متقدم لحاجة شخصية',
                        'is_settled' => rand(0, 1) === 1,
                        'settled_date' => rand(0, 1) === 1 ? Carbon::today() : null,
                    ]);
                }
            }
        }
    }
}
