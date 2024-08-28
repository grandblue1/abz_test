<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
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
        User::factory()->count(1000)->create();

    }
}
