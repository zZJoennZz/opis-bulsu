<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemCategoryGroup;

class ItemGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ItemCategoryGroup::create([
            'title' => 'N/A',
            'report_sub_total_footer' => 'N/A',
            'order' => 0,
            'under_of_section' => 1
        ]);
        ItemCategoryGroup::create([
            'title' => 'OFFICE SUPPLIES',
            'report_sub_total_footer' => 'OFFICE SUPPLIES',
            'order' => 1,
            'under_of_section' => 1
        ]);
        ItemCategoryGroup::create([
            'title' => 'OTHER SUPPLIES & MATERIALS',
            'report_sub_total_footer' => 'OTHER SUPPLIES & MATERIALS',
            'order' => 2,
            'under_of_section' => 1
        ]);
        ItemCategoryGroup::create([
            'title' => 'SUBSCRIPTION EXPENSES',
            'report_sub_total_footer' => 'SUBSCRIPTION EXPENSES',
            'order' => 3,
            'under_of_section' => 1
        ]);
        ItemCategoryGroup::create([
            'title' => 'Others',
            'report_sub_total_footer' => 'Others',
            'order' => 0,
            'under_of_section' => 1
        ]);
        ItemCategoryGroup::create([
            'title' => 'N/A',
            'report_sub_total_footer' => 'N/A',
            'order' => 0,
            'under_of_section' => 2
        ]);
    }
}
