<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Attendance;
use Tests\TestCase;

class AdminAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_view_attendance_list()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'role' => 'user',
            'name' => 'テスト',
        ]);

        Attendance::factory()->create([
            'user_id' => $staff->id,
            'work_date' => now()->toDateString(),
        ]);

        $response = $this
        ->actingAs($admin)
        ->get('/admin/attendance/list');

        $response->assertStatus(200);

        $response->assertSee('テスト');
    }

    public function test_current_date_is_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this
        ->actingAs($admin)
        ->get('/admin/attendance/list');

        $response->assertStatus(200);

        $response->assertSee(now()->format('Y/m/d'));
    }

    public function test_previous_day_attendance_can_be_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $yesterday = now()->subDay();

        $response = $this
        ->actingAs($admin)
        ->get('/admin/attendance/list?date=' . $yesterday->format('Y-m-d'));

        $response->assertStatus(200);

        $response->assertSee($yesterday->format('Y/m/d'));
    }

    public function test_next_day_attendance_can_be_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $tomorrow = now()->addDay();

        $response = $this
        ->actingAs($admin)
        ->get('/admin/attendance/list?date=' . $tomorrow->format('Y-m-d'));

        $response->assertStatus(200);

        $response->assertSee($tomorrow->format('Y/m/d'));
    }

    public function test_all_users_attendance_are_displayed()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $staff1 = User::factory()->create([
            'role' => 'user',
            'name' => 'テスト１',
        ]);

        $staff2 = User::factory()->create([
            'role' => 'user',
            'name' => 'テスト２',
        ]);

        Attendance::factory()->create([
            'user_id' => $staff1->id,
            'work_date' => today(),
        ]);

        Attendance::factory()->create([
            'user_id' => $staff2->id,
            'work_date' => today(),
        ]);

        $response = $this
        ->actingAs($admin)
        ->get('/admin/attendance/list');

        $response->assertStatus(200);

        $response->assertSee('テスト１');
        $response->assertSee('テスト２');
    }
}
