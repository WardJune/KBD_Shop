<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeycapTypeSeeder extends Seeder
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
                'name' => 'Add-On',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Artisan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cherry MX Sets',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Spacebar',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
        ];

        DB::table('keycap_types')->insert($data);
    }
}
