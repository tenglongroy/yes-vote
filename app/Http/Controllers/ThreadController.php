<?php

namespace App\Http\Controllers;

use App\Thread;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function show($entryCode)
    {
        $thread = Thread::where('entryCode', $entryCode)->first();
        $comments = $thread->comments;
        $votes = $thread->votes;
        $voteArray = array();
        foreach ($votes as $vote) { //get the vote, its choices and the corresponding selections
            $tempArray = array();
            $sum = 0;
            foreach ($vote->choices as $choice) {
                $tempCount = $thread->selections()->where('vote_id', $vote->id)->where('choice_id', $choice->id)->count();
                $sum += $tempCount;
                array_push($tempArray, [$choice, $tempCount]);
            }
            array_push($voteArray, [$vote, $tempArray, $sum]);
        }
        //dd($votes, $voteArray);

        /*$selectionArray = array();
        foreach ($votes as $vote) {     //calculate the corresponding number of votes
            $tempArray = array();
            $sum = 0;
            foreach ($vote->choices as $choice) {
                $tempCount = $thread->selections()->where('vote_id', $vote->id)->where('choice_id', $choice->id)->count();
                $sum += $tempCount;
                array_push($tempArray, $tempCount);
            }
            array_push($tempArray, $sum);
            array_push($selectionArray, $tempArray);
        }*/

        $timeBeforeNextVote = null;
        if(Auth::check()) {     //show user's last vote choice
            $userLastVote = array();
            foreach ($votes as $vote) {
                break;
            }
            $userLastVoteTime = $thread->selections()->where('user_id', Auth::user()->id)->first()['created_at'];
            $timeGap = (strtotime(Carbon::now()) - strtotime($userLastVoteTime))/60;
            if( (int)$timeGap <= $thread->voteGap) {
                //time gap is bigger than vote gap now
                $timeBeforeNextVote = $thread->voteGap - (int)$timeGap;
            }
        }

        //dd($userLastVoteTime);
        return view('threads.show', compact('thread', 'comments', 'voteArray', 'timeBeforeNextVote'));
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
