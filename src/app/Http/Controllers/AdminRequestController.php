<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceChangeRequest;

class AdminRequestController extends Controller
{
    public function showRequestApproval($attendance_correct_request_id)
    {
        $changeRequest = AttendanceChangeRequest::with([
            'attendance.user',
            'breakChangeRequests'
        ])->findOrFail($attendance_correct_request_id);

        return view('admin.request_approval', compact('changeRequest'));
    }

    public function requestApproval($attendance_correct_request_id)
    {
        $changeRequest = AttendanceChangeRequest::findOrFail($attendance_correct_request_id);

        $changeRequest->update([
            'status' => 'approved',
        ]);

        return redirect()->route('admin-staff.request-approval.show', $changeRequest->id);
    }
}
