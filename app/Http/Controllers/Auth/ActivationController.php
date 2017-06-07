<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use App\Notifications\SendActivationEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivationController extends Controller
{
    //
    public function activate($token)
    {
        // find token based on id
        $user = User::where('activation_token', $token)->first();

        if ($user) {

            // update activation account details
            $user->activation_token = null;
            $user->send_time = null;
            $user->activated_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();

            // login using id
            auth()->loginUsingId($user->id);

            flash()->overlay('You can now create a vote.','Activated');
            // redirect to home
            return redirect()->route('home');
        } else {
            return 'invalid token provided';
        }
    }

    public function reactivate(){
        error_log('in reactivate');
        $user = auth()->user();
        error_log('user email'.$user->email);
        if(empty($user->activated_at)){ //not yet activated, continue
            $token = str_random(64);
            $user->activation_token = $token;
            $user->send_time = Carbon::now();
            $user->save();

            // send notification
            $user->notify(
                new SendActivationEmail($token, $user)
            );

            return back();
        }
        else{
            return view('wasteland');
        }
    }
}
