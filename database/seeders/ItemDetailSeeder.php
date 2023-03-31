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
        $articleForTest = "For testing";
        ItemDetail::create([
            'description' => 'Pencil',
            'article' => $articleForTest,
            'price_catalogue' => 50,
            'category_id' => 1,
            'unit_id' => 6,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => 'Ballpen',
            'article' => $articleForTest,
            'price_catalogue' => 50,
            'category_id' => 1,
            'unit_id' => 6,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => 'Plastic Folder',
            'article' => $articleForTest,
            'price_catalogue' => 50,
            'category_id' => 1,
            'unit_id' => 6,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => '22"in Monitor 60hz',
            'article' => $articleForTest,
            'price_catalogue' => 5000,
            'category_id' => 7,
            'unit_id' => 5,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => '24"in Monitor 60hz',
            'article' => $articleForTest,
            'price_catalogue' => 7000,
            'category_id' => 7,
            'unit_id' => 5,
            'added_by' => 1,
            'is_approve' => 1
        ]);

        ItemDetail::create([
            'description' => 'Dual Monitor Stand',
            'article' => $articleForTest,
            'price_catalogue' => 2000,
            'category_id' => 6,
            'unit_id' => 5,
            'added_by' => 1,
            'is_approve' => 1
        ]);
    }
}
