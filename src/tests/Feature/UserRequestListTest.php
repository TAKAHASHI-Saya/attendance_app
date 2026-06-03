<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceChangeRequest;
use Tests\TestCase;

class UserRequestListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_pending_requests_are_displayed()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        AttendanceChangeRequest::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'reason' => '承認待ち',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('stamp-correction-request.list'));

        $response->assertStatus(200);

        $response->assertSee('承認待ち');
    }

    public function test_user_approved_requests_are_displayed()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        AttendanceChangeRequest::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'status' => 'approved',
            'reason' => '承認済',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('stamp-correction-request.list'));

        $response->assertStatus(200);

        $response->assertSee('承認済');
    }

    public function test_user_cannot_see_other_users_requests()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $otherUser = User::factory()->create([
            'role' => 'user',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        AttendanceChangeRequest::factory()->create([
            'user_id' => $otherUser->id,
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'reason' => '他人の申請',
        ]);

        $response = $this
        ->actingAs($user)
        ->get(route('stamp-correction-request.list'));

        $response->assertStatus(200);

        $response->assertDontSee('他人の申請');
    }
}
