<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ItemDetail;
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
        $this->call([
            BranchSeeder::class,
            UserSeeder::class,
            ItemGroupSectionSeeder::class,
            ItemGroupSeeder::class,
            ItemCategorySeeder::class,
            UnitSeeder::class,
            ItemDetailSeeder::class
        ]);
    }
}
