<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AttendanceChangeRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'attendance_id',
        'after_clock_in_at',
        'after_clock_out_at',
        'reason',
        'status',
    ];

    protected $casts = [
        'after_clock_in_at' => 'datetime',
        'after_clock_out_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendance(){
        return $this->belongsTo(Attendance::class);
    }

    public function breakChangeRequests(){
        return $this->hasMany(BreakChangeRequest::class);
    }

    protected function statusLabel(): Attribute
    {
        return new Attribute(
            get: fn() => match($this->status){
                'pending' => '承認待ち',
                'approved' => '承認済み',
                default => '',
            }
        );
    }
}
