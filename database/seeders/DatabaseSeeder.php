<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'username' => 'admin',
            'type' => "admin",
            'password' => Hash::make('password'),
        ]);
        
        User::create([
            'username' => env('ADMIN_USERNAME'),
            'type' => "admin",
            'password' => Hash::make(env('ADMIN_PASSWORD')),
        ]);
    }
}
