<?php

namespace App\Http\Controllers;

use App\Vote;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        //only auth can make it through the ctor
        $this->middleware('auth');
    }

    public function store($entryCode)
    {
        $this->validate(request(), ['body'=>'required|min:2']);
//        Comment::create([
//           'body' => request('body'),
//            'post_id' => $post->id
//        ]);
        $vote = Vote::where('entryCode', $entryCode)->first();
        $vote->addComment(request('body'));
        return back();
    }
}
