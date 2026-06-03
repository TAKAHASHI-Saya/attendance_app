<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AdminRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// メール認証機能
Route::get('/email/verify', function(){
    return view('user.verify_email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();
    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function(Request $request){
    $request->user()
    ->sendEmailVerificationNotification();
    return back();
})->middleware(['auth', 'throttle:6.1'])->name('verification.send');

Route::get('/', function(){
    return redirect('/login');
});

// 共通
Route::middleware(['auth'])->group(function(){
    Route::get('/stamp_correction_request/list', [CommonController::class, 'index'])->name('stamp-correction-request.list');
});

// 一般ユーザー
Route::middleware(['auth','verified', 'role:user'])->group(function()
{

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('user-attendance.index');

    Route::post('/clock-in', [AttendanceController::class, 'clockIn']);

    Route::post('/break-in', [AttendanceController::class, 'breakIn']);

    Route::post('/break-out', [AttendanceController::class, 'breakOut']);

    Route::post('/clock-out', [AttendanceController::class, 'clockOut']);

    Route::get('/attendance/list', [RecordController::class, 'index'])->name('user-attendance.record');

    Route::get('/attendance/detail/{id}', [RecordController::class, 'showDetail'])->name('user-attendance.detail');

    Route::post('/attendance/detail/{id}/request', [RecordController::class, 'storeChangeRequest'])->name('user-attendance.request.store');
});

// 管理者
Route::get('/admin/login', function(){
    return view('admin.login');
})->name('admin.login');

Route::middleware(['auth', 'role:admin'])->group(function()
{
    Route::get('/admin/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin-attendance.index');

    Route::get('/admin/attendance/{id}', [AdminAttendanceController::class, 'showDetail'])->name('admin-attendance.detail');

    Route::patch('/admin/attendance-update/{id}', [AdminAttendanceController::class, 'update'])->name('admin-attendance.update');

    Route::get('/admin/staff/list', [AdminStaffController::class, 'showStaffList'])->name('admin-staff.list.show');

    Route::get('/admin/attendance/staff/{id}', [AdminStaffController::class, 'showStaffAttendance'])->name('admin-staff.attendance.show');

    Route::get('/admin/attendance/staff/{id}/csv', [AdminStaffController::class, 'exportCsv'])->name('admin-staff.attendance.csv');

    Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [AdminRequestController::class, 'showRequestApproval'])->name('admin-staff.request-approval.show');

    Route::patch('/stamp_correction_request/accept/{attendance_correct_request_id}', [AdminRequestController::class, 'requestApproval'])->name('admin-staff.request-approval.update');
});