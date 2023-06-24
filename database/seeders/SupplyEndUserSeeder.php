<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\SupplyEndUser;

class SupplyEndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        for ($i = 0; $i <= 15; $i++) {
            SupplyEndUser::create([
                'first_name' => $faker->firstName(),
                'middle_name' => $faker->lastName(),
                'last_name' => $faker->lastName(),
                'branches_id' => rand(2, 10),
                'supply_positions_id' => rand(1, 5),
                'is_delete' => 0,
                'added_by' => 1,
            ]);
        }
    }
}
