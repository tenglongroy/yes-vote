<?php

namespace App\Http\Controllers;

use App\User;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['wasteland', 'search']);
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
        /*$selections = $user->selections()->latest()->select('vote_id')->groupBy('vote_id')->get();
        $selection_votes = array();
        foreach ($selections as $selection){
            array_push($selection_votes, Vote::where('id', $selection->vote_id)->first());
        }*/
        $selections = DB::table('votes')->join('selections', 'selections.vote_id', '=', 'votes.id')
            ->where('selections.user_id', $user->id)->groupBy('vote_id')
            ->selectRaw('votes.*, selections.created_at as select_time')->orderBy('select_time', 'desc')->get();
        //dd($selections);
        return view('votes.user', compact('user', 'votes', 'selections'));
    }

    public function changeName(Request $request)
    {
        $newName = $request->input('name_update_ipt');
        $user = auth()->user();
        $user->name = $newName;
        $user->save();
        flash()->overlay("Your name has been updated.", 'Success!');
        return back();
    }

    public function changePassword(Request $request)
    {
        error_log($request->input('old_password'));
        error_log($request->input('password'));
        error_log($request->input('confirm_password'));
        $rule = [
            'old_password' => 'required|filled',
            'password' => 'required|min:8|confirmed'
        ];
        $this->validate($request, $rule);

        $user = auth()->user();
        if( !Hash::check($request->input('old_password',' '), $user->password)){
            return back()->withErrors(['Old password not correct.']);
        }

        $user->password = bcrypt($request->input('password'));
        $user->save();
        flash()->overlay("Your password has been updated.", 'Success!');

        return redirect('/users/'.$user->id);
    }

    public function wasteland()
    {
        return view('wasteland');
    }

    public function search(){
        error_log('in search');
        //dd(request());
        return response()->json(Vote::first());
    }
}
