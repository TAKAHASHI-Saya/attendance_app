<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use Tests\TestCase;

class UserAttendanceRequestTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_reason_is_required()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => '18:00:00',
        ]);

        $response = $this
        ->actingAs($user)
        ->from(route('user-attendance.detail', $attendance->id))
        ->post(route('user-attendance.request.store', $attendance->id),[
            'after_clock_in_at' => '09:00',
            'after_clock_out_at' => '18:00',
            'after_break_in_at' => [],
            'after_break_out_at' => [],
            'reason' => '',
        ]);

        $response->assertSessionHasErrors('reason');
    }

    public function test_clock_out_must_be_after_clock_in()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
        ->actingAs($user)
        ->from(route('user-attendance.detail', $attendance->id))
        ->post(route('user-attendance.request.store', $attendance->id),[
            'after_clock_in_at' => '18:00',
            'after_clock_out_at' => '09:00',
            'after_break_in_at' => [],
            'after_break_out_at' => [],
            'reason' => 'テスト',
        ]);

        $response->assertSessionHasErrors('after_clock_out_at');
    }

    public function test_break_start_must_be_before_clock_out()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
        ->actingAs($user)
        ->from(route('user-attendance.detail', $attendance->id))
        ->post(route('user-attendance.request.store', $attendance->id),[
            'after_clock_in_at' => '09:00',
            'after_clock_out_at' => '18:00',
            'after_break_in_at' => ['19:00'],
            'after_break_out_at' => ['20:00'],
            'reason' => 'テスト',
        ]);

        $response->assertSessionHasErrors('after_break_in_at.0');
    }

    public function test_break_out_must_be_before_clock_out()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
        ->actingAs($user)
        ->from(route('user-attendance.detail', $attendance->id))
        ->post(route('user-attendance.request.store', $attendance->id),[
            'after_clock_in_at' => '09:00',
            'after_clock_out_at' => '18:00',
            'after_break_in_at' => ['12:00'],
            'after_break_out_at' => ['19:00'],
            'reason' => 'テスト',
        ]);

        $response->assertSessionHasErrors('after_break_out_at.0');
    }

    public function test_attendance_change_request_can_be_created()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
        ->actingAs($user)
        ->post(route('user-attendance.request.store', $attendance->id),
        [
            'after_clock_in_at' => '09:00',
            'after_clock_out_at' => '18:00',
            'after_break_in_at' => [],
            'after_break_out_at' => [],
            'reason' => '理由',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('attendance_change_requests',
        [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'reason' => '理由',
            'status' => 'pending',
        ]);
    }
}
