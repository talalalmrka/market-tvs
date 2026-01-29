<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'talalminfo@gmail.com',
            'password' => Hash::make('raysh77@@'),
        ])->each(fn(User $user) => $user->syncRoles('admin'));

        User::factory(29)->create()->each(fn(User $user) => $user->syncRoles('customer'));
    }
}
