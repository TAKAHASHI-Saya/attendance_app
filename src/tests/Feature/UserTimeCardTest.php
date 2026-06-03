<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\RestBreak;
use Tests\TestCase;

class UserTimeCardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_status_is_off_duty_when_no_attendance()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.index'));

        $response->assertStatus(200);

        $response->assertSee('勤務外');
        $response->assertSee('出勤');
    }

    public function test_user_can_clock_in()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($user)
        ->post('/clock-in');

        $response->assertRedirect('/attendance');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'work_date' => today(),
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.index'));

        $response->assertSee('出勤中');
    }

    public function test_clock_in_button_is_not_displayed_after_clock_out()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => '18:00:00',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.index'));

        $response->assertSee('退勤済');
        $response->assertDontSee('出勤');
    }

    public function test_user_can_start_break()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => null,
        ]);

        $response = $this
        ->actingAs($user)
        ->post('/break-in');

        $response->assertRedirect('/attendance');

        $this->assertDatabaseHas('rest_breaks', [
            'attendance_id' => $attendance->id,
        ]);
    }

    public function test_break_out_button_is_displayed_while_on_break()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => null,
        ]);

        RestBreak::factory()->create([
            'attendance_id' => $attendance->id,
            'break_in_at' => '12:00:00',
            'break_out_at' => null,
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.index'));

        $response->assertSee('休憩中');
        $response->assertSee('休憩戻');
    }

    public function test_user_can_end_break()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => null,
        ]);

        $break = RestBreak::factory()->create([
            'attendance_id' => $attendance->id,
            'break_in_at' => '12:00:00',
            'break_out_at' => null,
        ]);

        $response = $this
        ->actingAs($user)
        ->post('/break-out');

        $response->assertRedirect('/attendance');

        $this->assertDatabaseMissing('rest_breaks', [
            'id' => $break->id,
            'break_out_at' => null,
        ]);
    }

    public function test_clock_out_and_break_in_buttons_are_displayed_while_working()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => null,
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.index'));

        $response->assertSee('出勤中');
        $response->assertSee('退勤');
        $response->assertSee('休憩入');
    }

    public function test_user_can_clock_out()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => null,
        ]);

        $response = $this
        ->actingAs($user)
        ->post('/clock-out');

        $response->assertRedirect('/attendance');

        $this->assertDatabaseMissing('attendances', [
            'id' => $attendance->id,
            'clock_out_at' => null,
        ]);
    }

    public function test_finish_message_is_displayed_after_clock_out()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => '18:00:00',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.index'));

        $response->assertSee('退勤済');
        $response->assertSee('お疲れ様でした');
    }

    public function test_clock_out_button_is_not_displayed_while_on_break()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => today(),
            'clock_in_at' => '09:00:00',
            'clock_out_at' => null,
        ]);

        RestBreak::factory()->create([
            'attendance_id' => $attendance->id,
            'break_in_at' => '12:00:00',
            'break_out_at' => null,
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.index'));

        $response->assertSee('休憩戻');
        $response->assertDontSee('退勤');
    }
}
