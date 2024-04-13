<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Master Admin',
            'username' => 'masteradmin',
            'email' => 'admin@gmail.com',
            'level' => 10,
            'password' => bcrypt('Setting123'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->call([
            PrizeSeeder::class,
            UserVoucherSeeder::class,
            RewardSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
