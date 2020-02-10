<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword as ResetPasswordMail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails{
        sendResetLinkEmail as sendResetLinkEmailParent;
    }

    public function sendResetLinkEmail(Request $request)
    {
        ResetPassword::toMailUsing(function ($user, $token){
            return new ResetPasswordMail($user,$token);
        });

        return $this->sendResetLinkEmailParent($request);
    }
}
