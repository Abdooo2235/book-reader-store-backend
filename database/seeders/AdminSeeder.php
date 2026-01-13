<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  public function run(): void
  {
    // Delete existing admin to avoid duplicate
    User::where('email', 'admin@bookreader.com')->delete();

    // Create fresh admin (booted() auto-creates cart, collections, preferences)
    User::create([
      'name' => 'Admin',
      'email' => 'admin@bookreader.com',
      'password' => Hash::make('password'),
      'role' => 'admin',
    ]);

    echo "Admin user created: admin@bookreader.com / password\n";
  }
}
