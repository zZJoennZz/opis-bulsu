<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        $companies = [
            [
                'name' => 'Example Company 1',
                'full_address' => '123 Example Street, Example City, Example Country',
                'tin' => '123-456-789',
                'contact_number' => '+63 912 345 6789',
                'email_address' => 'example1@example.com',
                'is_in_philgeps' => 0,
                'is_delete' => 0,
                'added_by' => 1
            ],
            [
                'name' => 'Example Company 2',
                'full_address' => '456 Example Avenue, Example City, Example Country',
                'tin' => '987-654-321',
                'contact_number' => '+63 987 654 3210',
                'email_address' => 'example2@example.com',
                'is_in_philgeps' => 0,
                'is_delete' => 0,
                'added_by' => 1
            ],
            [
                'name' => 'Example Company 3',
                'full_address' => '789 Example Boulevard, Example City, Example Country',
                'tin' => '246-135-798',
                'contact_number' => '+63 246 135 3980',
                'email_address' => 'example3@example.com',
                'is_in_philgeps' => 0,
                'is_delete' => 0,
                'added_by' => 1
            ],
            [
                'name' => 'Example Company 4',
                'full_address' => '321 Example Road, Example City, Example Country',
                'tin' => '123-123-123',
                'contact_number' => '+63 246 135 7980',
                'email_address' => 'example4@example.com',
                'is_in_philgeps' => 0,
                'is_delete' => 0,
                'added_by' => 1
            ]
        ];

        foreach ($companies as $c) {
            Company::create([
                'name' => $c['name'],
                'full_address' => $c['full_address'],
                'tin' => $c['tin'],
                'contact_number' => $c['contact_number'],
                'email_address' => $c['email_address'],
                'is_in_philgeps' => $c['is_in_philgeps'],
                'is_delete' => $c['is_delete'],
                'added_by' => $c['added_by']
            ]);
        }
    }
}
