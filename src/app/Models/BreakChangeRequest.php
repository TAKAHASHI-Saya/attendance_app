<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_change_request_id',
        'rest_break_id',
        'after_break_in_at',
        'after_break_out_at',
    ];

    protected $casts = [
        'after_break_in_at' => 'datetime',
        'after_break_out_at' => 'datetime',
    ];

    public function attendanceChangeRequest(){
        return $this->belongsTo(AttendanceChangeRequest::class);
    }

    public function restBreak(){
        return $this->belongsTo(RestBreak::class);
    }
}
