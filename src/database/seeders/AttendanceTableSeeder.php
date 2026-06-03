<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;

class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::where('role', 'user')->get();

        $start = Carbon::now()->subMonth(3);
        $end = Carbon::yesterday();

        foreach ($users as $user){
            for ($date = $start->copy(); $date->lte($end); $date->addDay()){
                if ($date->isWeekday()){
                    Attendance::factory()->create([
                        'user_id' => $user->id,
                        'work_date' => $date->toDateString(),
                    ]);
                }
            }
        }
    }
}
