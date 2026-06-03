<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\RestBreak;
use Tests\TestCase;

class AdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_view_attendance_detail()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $staff->id,
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-attendance.detail', $attendance->id));

        $response->assertStatus(200);
    }

    public function test_staff_name_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
            'name' => 'テスト',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $staff->id,
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-attendance.detail', $attendance->id));

        $response->assertSee('テスト');
    }
    
    public function test_work_date_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $staff->id,
            'work_date' => '2025-06-01',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-attendance.detail', $attendance->id));

        $response->assertSee('2025年');
        $response->assertSee('6月1日');
    }

    public function test_clock_in_time_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $staff->id,
            'clock_in_at' => '09:00:00',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-attendance.detail', $attendance->id));

        $response->assertSee('09:00');
    }

    public function test_clock_out_time_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $staff->id,
            'clock_out_at' => '18:00:00',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-attendance.detail', $attendance->id));

        $response->assertSee('18:00');
    }

    public function test_rest_break_time_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $staff->id,
        ]);

        RestBreak::factory()->create([
            'attendance_id' => $attendance->id,
            'break_in_at' => '12:00:00',
            'break_out_at' => '13:00:00',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-attendance.detail', $attendance->id));

        $response->assertSee('12:00');
        $response->assertSee('13:00');
    }
}
