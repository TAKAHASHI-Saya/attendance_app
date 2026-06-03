<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use Tests\TestCase;

class AdminStaffListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_view_staff_list()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.list.show'));

        $response->assertStatus(200);
    }

    public function test_staff_names_are_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
            'name' => 'テスト',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.list.show'));

        $response->assertSee('テスト');
    }

    public function test_staff_email_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
            'email' => 'test@example.com',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.list.show'));

        $response->assertSee('test@example.com');
    }

    public function test_admin_is_not_displayed_in_staff_list()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => '管理者',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.list.show'));

        $response->assertDontSee('管理者');
    }

    public function test_admin_can_view_staff_attendance()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.attendance.show', $staff->id));

        $response->assertStatus(200);
    }

    public function test_staff_name_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
            'name' => 'テスト'
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.attendance.show', $staff->id));

        $response->assertSee('テスト');
    }

    public function test_attendance_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::factory()->create([
            'user_id' => $staff->id,
            'work_date' => '2026-06-03',
            'clock_in_at' => '09:00:00',
            'clock_out_at' => '18:00:00',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.attendance.show', $staff->id));

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    public function test_previous_month_attendance_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::factory()->create([
            'user_id' => $staff->id,
            'work_date' => '2026-06-03',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.attendance.show', [
            'id' => $staff->id,
            'month' => '2026-06',
        ]));

        $response->assertStatus(200);
    }

    public function test_current_month_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.attendance.show', [
            'id' => $staff->id,
            'month' => '2026-06',
        ]));

        $response->assertSee('2026/06');
    }

    public function test_previous_month_can_be_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.attendance.show', [
            'id' => $staff->id,
            'month' => '2026-05',
        ]));

        $response->assertSee('2026/05');
    }

    public function test_next_month_can_be_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get(route('admin-staff.attendance.show', [
            'id' => $staff->id,
            'month' => '2026-07',
        ]));

        $response->assertSee('2026/07');
    }
}
