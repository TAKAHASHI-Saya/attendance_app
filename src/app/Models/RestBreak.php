<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'break_in_at',
        'break_out_at',
    ];

    protected $casts = [
        'break_in_at' => 'datetime',
        'break_out_at' => 'datetime',
    ];

    public function attendance(){
        return $this->belongsTo(Attendance::class);
    }

    public function breakChangeRequests(){
        return $this->hasMany(RestBreak::class);
    }
}
