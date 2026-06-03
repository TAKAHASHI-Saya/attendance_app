<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        if(str_contains(url()->previous(), '/admin')){
            return redirect('/admin/login');
        }

        return redirect('/login');
    }
}