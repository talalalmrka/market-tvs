<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            FontSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            ScreenSeeder::class,
            CommentSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
