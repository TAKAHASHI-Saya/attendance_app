<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestBreak;
use App\Models\Attendance;

class RestBreakTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = Attendance::all();
        
        foreach(range(1, 300) as $i){
            $attendance = $attendances->random();
            
            RestBreak::factory()->create([
                'attendance_id' => $attendance->id,
            ]);

        }
    }
}
