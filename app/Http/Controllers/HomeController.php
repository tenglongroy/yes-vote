<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function user(User $user)
    {
        if ($user->id != auth()->user()->id)
            return redirect('wasteland');
        //$currentUser = auth()->user();
        $votes = $user->votes()->latest()->get();
        $selections = $user->selections()->latest()->select('vote_id')->groupBy('vote_id')->get();
        return view('votes.user', compact('user', 'votes', 'selections'));
    }

    public function change()
    {

    }

    public function wasteland()
    {
        return view('wasteland');
    }
}
