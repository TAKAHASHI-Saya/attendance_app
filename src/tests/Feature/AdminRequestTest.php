<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\AttendanceChangeRequest;
use App\Models\Attendance;
use Tests\TestCase;

class AdminRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_pending_requests_are_displayed()
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

        AttendanceChangeRequest::factory()->create([
            'user_id' => $staff->id,
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'reason' => '承認待ち１',
        ]);

        AttendanceChangeRequest::factory()->create([
            'user_id' => $staff->id,
            'attendance_id' => $attendance->id,
            'status' => 'pending',
            'reason' => '承認待ち２',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('stamp-correction-request.list'));

        $response->assertStatus(200);

        $response->assertSee('承認待ち１');
        $response->assertSee('承認待ち２');
    }

    public function test_approved_requests_are_displayed()
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

        AttendanceChangeRequest::factory()->create([
            'user_id' => $staff->id,
            'attendance_id' => $attendance->id,
            'status' => 'approved',
            'reason' => '承認済み',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('stamp-correction-request.list', [
            'tab' => 'approved',
        ]));

        $response->assertSee('承認済み');
    }

    public function test_request_detail_is_displayed()
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

        $request = AttendanceChangeRequest::factory()->create([
            'user_id' => $staff->id,
            'attendance_id' => $attendance->id,
            'reason' => '理由',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('stamp-correction-request.list', $request->id));

        $response->assertStatus(200);

        $response->assertSee('理由');
    }

    public function test_request_can_be_approved()
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

        $changeRequest = AttendanceChangeRequest::factory()->create([
            'user_id' => $staff->id,
            'attendance_id' => $attendance->id,
            'status' => 'pending',
        ]);

        $response = $this
        ->actingAs($admin)
        ->patch(route('admin-staff.request-approval.update', $changeRequest->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('attendance_change_requests',
        [
            'id' => $changeRequest->id,
            'status' => 'approved'
        ]);
    }
}
