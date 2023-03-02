<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            ItemDetailSeeder::class,
            MilestoneFormatSeeder::class,
            ItemPurposeSeeder::class,
            SourceOfFundSeeder::class,
            SettingSeeder::class,
            PurchaseOrderModeOfPaymentSeeder::class,
        ]);
    }
}
