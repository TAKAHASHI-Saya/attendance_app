<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceChangeRequest;

class CommonController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->tab ?? 'pending';

        if(auth()->user()->role === 'admin'){
            $changeRequests = AttendanceChangeRequest::with('attendance.user')
            ->where('status', $activeTab)
            ->latest()
            ->get();

            return view('admin.change_request', compact('changeRequests', 'activeTab'));
        }

        $changeRequests = AttendanceChangeRequest::with('attendance.user')
        ->where('user_id', auth()->id())
        ->where('status', $activeTab)
        ->latest()
        ->get();

        return view('user.change_request', compact('changeRequests', 'activeTab'));
    }
}
