<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  public function run(): void
  {
    // Create or update admin user (handles soft deletes if email exists)
    $admin = User::withTrashed()->updateOrCreate(
      ['email' => 'admin@bookreader.com'],
      [
        'name' => 'Admin',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'deleted_at' => null, // Restore if was soft deleted
      ]
    );

    // Ensure relationships exist (since updateOrCreate might not trigger created event if updated)
    if (!$admin->wasRecentlyCreated) {
        $admin->cart()->firstOrCreate([]);
        $admin->preferences()->firstOrCreate([]);
        foreach (['Reading', 'Already Read', 'Planning', 'Favorites'] as $name) {
            $admin->collections()->firstOrCreate(['name' => $name], ['is_default' => true]);
        }
    }

    echo "Admin user created: admin@bookreader.com / password\n";
  }
}
