<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin_user = User::factory()->create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin12345678'),
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => '一般ユーザー',
            'email' => 'user@example.com',
            'email_verified_at' => null,
            'password' => bcrypt('user12345678'),
            'role' => 'user',
        ]);

        User::factory()->count(5)->create();

        $this->call([
            AttendanceTableSeeder::class,
            RestBreakTableSeeder::class,
            AttendanceChangeRequestTableSeeder::class,
            BreakChangeRequestTableSeeder::class,
        ]);
    }
}
