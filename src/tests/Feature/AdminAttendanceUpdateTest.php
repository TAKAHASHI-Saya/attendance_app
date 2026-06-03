<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use Tests\TestCase;

class AdminAttendanceUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_update_attendance()
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
            'clock_out_at' => '18:00:00',
        ]);

        $response = $this
        ->actingAs($admin)
        ->patch(route('admin-attendance.update', $attendance->id),
        [
            'clock_in_at' => '10:00',
            'clock_out_at' => '19:00',
            'break_id' => [],
            'break_in_at' => [],
            'break_out_at' => [],
            'reason' => '修正テスト',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('attendances',
        [
            'id' => $attendance->id,
            'clock_in_at' => '10:00:00',
            'clock_out_at' => '19:00:00',
        ]);
    }

    public function test_clock_out_must_be_after_clock_in()
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
        ->from(route('admin-attendance.detail', $attendance->id))
        ->patch(route('admin-attendance.update', $attendance->id),
        [
            'clock_in_at' => '18:00',
            'clock_out_at' => '09:00',
            'reason' => 'テスト',
        ]);

        $response->assertRedirect(route('admin-attendance.detail', $attendance->id));

        $response->assertSessionHasErrors([
            'clock_out_at' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_reason_is_required()
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
        ->from(route('admin-attendance.detail', $attendance->id))
        ->patch(route('admin-attendance.update', $attendance->id),
        [
            'clock_in_at' => '09:00',
            'clock_out_at' => '18:00',
            'reason' => '',
        ]);

        $response->assertRedirect(route('admin-attendance.detail', $attendance->id));

        $response->assertSessionHasErrors([
            'reason' => '備考を記入してください',
        ]);
    }

    public function rest_break_start_must_be_after_clock_in()
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
        ->from(route('admin-attendance.detail', $attendance->id))
        ->patch(route('admin-attendance.update', $attendance->id),
        [
            'clock_in_at' => '09:00',
            'clock_out_at' => '18:00',
            'break_in_at' => ['08:00'],
            'break_out_at' => ['12:00'],
            'reason' => 'テスト',
        ]);

        $response->assertSessionHasErrors([
            'break_in_at.0' => '休憩時間が不適切な値です',
        ]);
    }
}
