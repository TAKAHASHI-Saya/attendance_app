<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in_at',
        'clock_out_at',
        'reason',
    ];

    protected $casts = [
        'work_date' => 'date',
        'clock_in_at' => 'datetime',
        'clock_out_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function attendanceChangeRequest(){
        return $this->hasOne(AttendanceChangeRequest::class);
    }

    public function restBreaks(){
        return $this->hasMany(RestBreak::class);
    }

    protected function status(): Attribute{
        return  new Attribute(
            get: function(){
                if(is_null($this->clock_in_at)){
                    return '勤務外';
                }

                $latestBreak = $this->restBreaks()->latest('break_in_at')->first();

                if($latestBreak && !is_null($latestBreak->break_in_at) && is_null($latestBreak->break_out_at)){
                    return '休憩中';
                }
                
                if(!is_null($this->clock_out_at)){
                    return '退勤済';
                }

                return '出勤中';

            }
        );
    }
}


