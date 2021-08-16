<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeySwitchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Cherry',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gateron',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Kailh',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Matias',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Romer-G',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Topre',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Topre',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Outemu',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('key_switchs')->insert($data);
    }
}
