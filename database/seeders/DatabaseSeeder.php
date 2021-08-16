<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([UsersTableSeeder::class]);
        $this->call(ProvinceSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MerkSeeder::class);
        $this->call(KeyboardSizeSeeder::class);
        $this->call(KeycapTypeSeeder::class);
        $this->call(KeyboardSizeSeeder::class);
    }
}
