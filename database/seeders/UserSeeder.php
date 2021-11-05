<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(){

    User::factory()->create([
      'firstname' => 'admin',
      'lastname' => 'admin',
      'username' => 'admin',
      'phone' => '0412-0000000',
      'email' => 'admin@admin.com',
      'password' => Hash::make('admin')
    ]);

    User::factory(9)->create();
  }
}
