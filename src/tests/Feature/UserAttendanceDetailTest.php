<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use Tests\TestCase;

class UserAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_view_attendance_detail()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'name' => 'テスト',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-06-03',
            'clock_in_at' => '09:00:00',
            'clock_out_at' => '18:00:00',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.detail', $attendance->id));

        $response->assertStatus(200);

        $response->assertSee('テスト');
        $response->assertSee('2026年');
        $response->assertSee('6月3日');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }
}
