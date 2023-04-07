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
        $products = [
            ['description' => 'Post-it Notes',        'article' => 'P001',        'price_catalogue' => 7.99,        'category_id' => 1,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            ['description' => 'Ballpoint Pens',        'article' => 'P002',        'price_catalogue' => 14.99,        'category_id' => 1,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            ['description' => 'Printer Paper',        'article' => 'P003',        'price_catalogue' => 24.99,        'category_id' => 1,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            ['description' => 'Stapler',        'article' => 'P004',        'price_catalogue' => 9.99,        'category_id' => 1,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            ['description' => 'File Folders',        'article' => 'P005',        'price_catalogue' => 12.99,        'category_id' => 1,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            ['description' => 'Coffee Maker',        'article' => 'A001',        'price_catalogue' => 59.99,        'category_id' => 2,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            ['description' => 'Microwave Oven',        'article' => 'A002',        'price_catalogue' => 129.99,        'category_id' => 2,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            ['description' => 'Electric Kettle',        'article' => 'A003',        'price_catalogue' => 24.99,        'category_id' => 2,        'unit_id' => 6,        'added_by' => 1,        'is_approve' => 1],
            array(
                'description' => 'Microwave',
                'article' => 'P004',
                'price_catalogue' => 99.99,
                'category_id' => 4,
                'unit_id' => 6,
                'added_by' => 1,
                'is_approve' => 1
            ),
            array(
                'description' => 'Sofa',
                'article' => 'P005',
                'price_catalogue' => 499.99,
                'category_id' => 5,
                'unit_id' => 6,
                'added_by' => 1,
                'is_approve' => 1
            ),
            array(
                'description' => 'Laptop',
                'article' => 'P006',
                'price_catalogue' => 799.99,
                'category_id' => 6,
                'unit_id' => 6,
                'added_by' => 1,
                'is_approve' => 1
            ),
            array(
                'description' => 'Refrigerator',
                'article' => 'P007',
                'price_catalogue' => 899.99,
                'category_id' => 4,
                'unit_id' => 6,
                'added_by' => 1,
                'is_approve' => 1
            ),
            array(
                'description' => 'Bed',
                'article' => 'P008',
                'price_catalogue' => 699.99,
                'category_id' => 5,
                'unit_id' => 6,
                'added_by' => 1,
                'is_approve' => 1
            ),
            array(
                'description' => "Smart TV",
                "article"      => "P009",
                "price_catalogue"   => "899.99",
                "category_id"      => "7",
                "unit_id"          => "6",
                "added_by"         => "1",
                "is_approve"       => "1"
            ),
            array(
                "description"     => "Washing Machine",
                "article"         => "P010",
                "price_catalogue"   => "799.99",
                "category_id"      => "4",
                "unit_id"          => "6",
                "added_by"         => "1",
                "is_approve"       => "1"
            ),
            array(
                "description"     => "Dining Table Set",
                "article"         => "P011",
                "price_catalogue"   => "399.99",
                "category_id"      => "5",
                "unit_id"          => "6",
                "added_by"         => "1",
                "is_approve"       => "1"
            ),
            array(
                "description"     => "Air Conditioner",
                "article"         => "P012",
                "price_catalogue"   => "699.99",
                "category_id"      => "4",
                "unit_id"          => "6",
                "added_by"         => "1",
                "is_approve"       => "1"
            ),
            array(
                "description"     => 'Smartphone',
                'article' => 'P013',
                'price_catalogue' => 599.99,
                'category_id' => 6,
                'unit_id' => 6,
                'added_by' => 1,
                "is_approve" => 1
            )
        ];

        foreach ($products as $p) {
            ItemDetail::create([
                'description' => $p['description'],
                'article' => $p['article'],
                'price_catalogue' => $p['price_catalogue'] * 50,
                'category_id' => $p['category_id'],
                'unit_id' => $p['unit_id'],
                'added_by' => $p['added_by'],
                'is_approve' => $p['is_approve']
            ]);
        }
    }
}
