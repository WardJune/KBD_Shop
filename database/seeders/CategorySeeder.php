<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
                'name' => 'Keyboard',
                'slug' => 'keyboard',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Keycaps',
                'slug' => 'keycaps',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Switch',
                'slug' => 'switch',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        Category::insert($data);
    }
}
