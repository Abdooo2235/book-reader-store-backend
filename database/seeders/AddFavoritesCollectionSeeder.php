<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AddFavoritesCollectionSeeder extends Seeder
{
  /**
   * Add "Favorites" collection to all existing users who don't have it.
   */
  public function run(): void
  {
    $count = 0;

    User::all()->each(function ($user) use (&$count) {
      if (!$user->collections()->where('name', 'Favorites')->exists()) {
        $user->collections()->create([
          'name' => 'Favorites',
          'is_default' => true,
        ]);
        $count++;
      }
    });

    echo "Added 'Favorites' collection to {$count} users.\n";
  }
}
