<?php

namespace Database\Seeders;

use App\Models\Worker;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    private $names = [
        'أحمد محمد',
        'علي حسن',
        'محمود عبده',
        'فاطمة القاضي',
        'عائشة خليل',
        'سارة راشد',
        'مريم ياسين',
        'هند إبراهيم',
    ];

    public function run(): void
    {
        $contractors = User::where('role', 'contractor')->get();

        foreach ($contractors as $contractor) {
            foreach (range(0, 4) as $i) {
                Worker::create([
                    'contractor_id' => $contractor->id,
                    'name' => $this->names[$i % count($this->names)],
                    'phone' => '+2010' . str_pad($contractor->id * 1000 + $i, 8, '0', STR_PAD_LEFT),
                    'national_id' => str_pad($i + 1, 14, '0', STR_PAD_LEFT),
                    'is_active' => true,
                ]);
            }
        }
    }
}
