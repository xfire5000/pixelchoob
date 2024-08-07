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
            RoleSeeder::class,
            SettingSeed::class,
        ]);
        // if (\App\Models\User::count() < 10) {
        //     \App\Models\User::factory(10)->create();
        // }
        // $this->call([
        //     SettingSeed::class,
        // ]);
        // if (\App\Models\ListCase::count() < 10) {
        //     \App\Models\ListCase::factory(10)->create();
        // }
        // if (\App\Models\ListItem::count() < 60) {
        //     \App\Models\ListItem::factory(60)->create();
        // }
    }
}
