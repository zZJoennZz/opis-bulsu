<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemDetail;

class ItemDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ItemDetail::create([
            'description' => 'TEST ITEM',
            'article' => 'Test',
            'price_catalogue' => 111,
            'category_id' => 1,
            'unit_id' => 1,
            'added_by' => 1
        ]);
    }
}
