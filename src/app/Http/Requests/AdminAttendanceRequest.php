<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in_at' => 'required',
            'clock_out_at' => 'required|after:clock_in_at',
            'reason' => 'required',
            'break_in_at.*' => 'nullable|after:clock_in_at|before:clock_out_at',
            'break_out_at.*' => 'nullable|before:clock_out_at',
        ];
    }

    public function messages()
    {
        return [
            'clock_out_at.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'break_in_at.*.after' => '休憩時間が不適切な値です',
            'break_in_at.*.before' => '休憩時間が不適切な値です',
            'break_out_at.*.before' => '休憩時間もしくは退勤時間が不適切な値です',
            'reason.required' => '備考を記入してください',
        ];
    }
}
