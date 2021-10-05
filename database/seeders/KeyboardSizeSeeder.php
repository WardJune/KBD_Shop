<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeyboardSizeSeeder extends Seeder
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
                'name' => 'Tiny (40%)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Compact (60%)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Compact (75%)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Compact (78%)',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Full Size',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NumPad',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tenkeyless',
                'created_at' => now(),
                'updated_at' => now()
            ],

        ];

        DB::table('keyboard_sizes')->insert($data);
    }
}
