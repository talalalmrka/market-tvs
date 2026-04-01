<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public static function createAdmin(): User
    {
        $admin = \App\Models\User::role('admin')->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'admin',
                'email' => 'talalminfo@gmail.com',
                'password' => Hash::make('raysh77@@'),
            ]);
            if ($admin) {
                $admin->syncRoles('admin');
            }
        }

        return $admin;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        self::createAdmin();
        /* User::factory()->create([
            'name' => 'admin',
            'email' => 'talalminfo@gmail.com',
            'password' => Hash::make('raysh77@@'),
        ])->each(fn(User $user) => $user->syncRoles('admin')); */

        User::factory(29)->create()->each(fn (User $user) => $user->syncRoles('customer'));
    }
}
