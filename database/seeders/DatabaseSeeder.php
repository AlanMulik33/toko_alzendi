<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
        'name' => 'Alzendi10', 
        'username' => 'alzendi10',
        'email' => 'alzendi@gmail.com', 
        'password' => bcrypt('alzendi7')
        ]);

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
