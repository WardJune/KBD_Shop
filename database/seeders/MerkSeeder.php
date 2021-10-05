<?php

namespace Database\Seeders;

use App\Models\Merk;
use Illuminate\Database\Seeder;

class MerkSeeder extends Seeder
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
                'name' => 'Vortex',
                'slug' => 'vortex',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Varmilo',
                'slug' => 'varmilo',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tai-Hao',
                'slug' => 'tai-hao',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'ZOMO',
                'slug' => 'zomo',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'TechKeys',
                'slug' => 'techkeys',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Gateron',
                'slug' => 'gateron',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Durock',
                'slug' => 'durock',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Outemu',
                'slug' => 'outemu',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],

        ];

        Merk::insert($data);
    }
}
