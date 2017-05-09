<?php

namespace App\Http\Controllers;

use App\Thread;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    //
    public function __construct()
    {
        //only auth can make it through the ctor
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(/*Tag $tag=null*/)
    {
        //$threads = \App\Post::latest();
        $threads = Thread::orderBy('created_at', 'desc');

        $threads = $threads->where('startTime', '<=', Carbon::now())
            ->where('endTime', '>=', Carbon::now());

        //$threads = $threads->filtertime(request(['month', 'year']));
        $threads = $threads->get();

//        $archives = Post::selectRaw('year(created_at) as year, monthname(created_at) as month, count(*) published')
//                    ->groupBy('year', 'month')
//                    ->orderByRaw('created_at DESC')
//                    ->get()->toArray();
        //$archives = Post::archives();

        return view('threads.index', compact('threads', 'archives'));
    }

    public function show(Thread $thread)
    {
        $comments = $thread->comments();
        $votes = $thread->votes();
        return view('threads.show', compact('thread', 'comments'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store1()
    {
        $post = new \App\Post;  //use App\Post;
        $post->title = request('title');
        $post->body = request('body');
        $post->save();

        return redirect('/postsindex');
    }
    public function store()
    {
        $this->validate(request(),[
            'title' => 'required|min:2|max:42',
            'body' => 'required'
        ]);

        auth()->user()->publish(
            new Post(request(['title', 'body']))
        );

//        Post::create([
//           'title' => request('title'),
//            'body' => request('body'),
//            'user_id' => auth()->user()->id //auth()->id()
//        ]);


        //\App\Post::create(request(['title', 'body']));

        //\App\Post::create(request()->all());    //collaborate with fillable in Post.php


        //session()->flash('message', 'Your post has been published');
        //flash('Your post has been published.')->warning()->important();
        flash()->overlay("Your post has been published.", 'Welcome!');

        return redirect('/postsindex');
    }
}
