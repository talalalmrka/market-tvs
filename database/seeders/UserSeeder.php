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
                'email_verified_at' => now(),
            ]);
            if ($admin) {
                $admin->syncRoles('admin');
            }
        }

        return $admin;
    }

    public static function createCustomer(): User
    {
        $customer = \App\Models\User::role('customer')->first();
        if (! $customer) {
            $customer = User::create([
                'name' => 'customer',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('raysh77@@'),
            ]);
            if ($customer) {
                $customer->syncRoles('customer');
            }
        }

        return $customer;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        self::createAdmin();
        self::createCustomer();
        User::factory(5)
            ->create()
            ->each(fn (User $user) => $user->syncRoles('customer'));
    }
}
