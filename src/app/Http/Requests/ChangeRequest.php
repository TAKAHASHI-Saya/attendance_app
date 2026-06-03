<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeRequest extends FormRequest
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
            'after_clock_in_at' => 'required',
            'after_clock_out_at' => 'required|after:after_clock_in_at',
            'reason' => 'required',
            'after_break_in_at.*' => 'nullable|after:after_clock_in_at|before:after_clock_out_at',
            'after_break_out_at.*' => 'nullable|before:after_clock_out_at',
        ];
    }

    public function messages()
    {
        return [
            'after_clock_out_at.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'after_break_in_at.*.after' => '休憩時間が不適切な値です',
            'after_break_in_at.*.before' => '休憩時間が不適切な値です',
            'after_break_out_at.*.before' => '休憩時間もしくは退勤時間が不適切な値です',
            'reason.required' => '備考を記入してください',
        ];
    }
}
