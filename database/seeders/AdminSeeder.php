<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
  public function run(): void
  {
    $email = 'admin@bookreader.com';

    // Check if admin already exists (including soft deleted)
    $existingAdmin = DB::table('users')
      ->where('email', $email)
      ->first();

    if ($existingAdmin) {
      // Admin exists - just update and restore if needed
      DB::table('users')
        ->where('email', $email)
        ->update([
          'name' => 'Admin',
          'password' => Hash::make('password'),
          'role' => 'admin',
          'deleted_at' => null,
          'updated_at' => now(),
        ]);

      echo "Admin user updated: admin@bookreader.com / password\n";
      return;
    }

    // Admin doesn't exist - create new
    $admin = User::create([
      'name' => 'Admin',
      'email' => $email,
      'password' => Hash::make('password'),
      'role' => 'admin',
    ]);

    echo "Admin user created: admin@bookreader.com / password\n";
  }
}
