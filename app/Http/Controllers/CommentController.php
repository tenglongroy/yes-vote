<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        //only auth can make it through the ctor
        $this->middleware('auth');
    }

    public function store(Thread $thread)
    {
        $this->validate(request(), ['body'=>'required|min:2']);
//        Comment::create([
//           'body' => request('body'),
//            'post_id' => $post->id
//        ]);
        $thread->addComment(request('body'));
        return back();
    }
}
