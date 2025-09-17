<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departement = [
            [
                'id' => Str::uuid(),
                'departement_name' => 'IT',
                'max_clock_in_time' => '08:30:00',
                'max_clock_out_time' => '17:00:00',
            ],
            [
                'id' => Str::uuid(),
                'departement_name' => 'Human Resources',
                'max_clock_in_time' => '09:00:00',
                'max_clock_out_time' => '18:00:00',
            ],
            [
                'id' => Str::uuid(),
                'departement_name' => 'Finance',
                'max_clock_in_time' => '08:00:00',
                'max_clock_out_time' => '16:30:00',
            ],
        ];

        foreach ($departement as $data) {
            Departement::create($data);
        }
    }
}
