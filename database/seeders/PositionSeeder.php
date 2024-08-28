<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Position::query()->insert([
            [
                "id" => 1,
                "name" => "Lawyer"
            ],
            [
                "id" => 2,
                "name" => "Content manager"
            ],
            [
                "id" => 3,
                "name" => "Security"
            ],
            [
                "id" => 4,
                "name" => "Designer"
            ]
        ]);
    }
}
