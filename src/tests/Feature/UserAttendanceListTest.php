<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use Tests\TestCase;

class UserAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_view_own_attendance_list()
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
        ->get(route('user-attendance.record'));

        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    public function test_current_month_is_displayed()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.record'));

        $response->assertStatus(200);

        $response->assertSee(now()->format('Y/m'));
    }

    public function test_previous_month_can_be_displayed()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.record', [
            'month' => '2026-05',
        ]));

        $response->assertSee('2026/05');
    }

    public function test_next_month_can_be_displayed()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.record', [
            'month' => '2026-07',
        ]));

        $response->assertSee('2026/07');
    }

    public function test_user_cannot_see_other_users_attendance()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $otherUser = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::factory()->create([
            'user_id' => $otherUser->id,
            'work_date' => today(),
            'clock_in_at' => '08:00:00',
            'clock_out_at' => '17:00:00',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('user-attendance.record'));

        $response->assertDontSee('08:00:00');
        $response->assertDontSee('17:00:00');
    }
}
