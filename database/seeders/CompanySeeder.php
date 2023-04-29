<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
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
        for ($i = 0; $i <= 25; $i++) {
            Company::create([
                'name' => $faker->company,
                'full_address' => $faker->address,
                'tin' => $faker->randomNumber,
                'contact_number' => $faker->phoneNumber,
                'email_address' => $faker->email,
                'is_in_philgeps' => 1,
                'philgeps_number' => $faker->randomNumber,
                'is_delete' => 0,
                'added_by' => 1
            ]);
        }
    }
}
