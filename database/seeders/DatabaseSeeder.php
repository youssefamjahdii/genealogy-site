<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
{
    // Run users seeder first
    $this->call(UsersTableSeeder::class);
    
    // Then seed the people table
    $this->call(PeopleTableSeeder::class);
}

}
