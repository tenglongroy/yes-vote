<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Selection;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function __construct()
    {
        //only auth can make it through the ctor
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(/*Tag $tag=null*/)
    {
        //$votes = \App\Post::latest();
        $votes = Vote::orderBy('created_at', 'desc');

        $votes = $votes->where('startTime', '<=', Carbon::now())
            ->where('endTime', '>=', Carbon::now());

        //$votes = $votes->filtertime(request(['month', 'year']));
        $votes = $votes->get();

//        $archives = Post::selectRaw('year(created_at) as year, monthname(created_at) as month, count(*) published')
//                    ->groupBy('year', 'month')
//                    ->orderByRaw('created_at DESC')
//                    ->get()->toArray();
        //$archives = Post::archives();

        return view('votes.index', compact('votes', 'archives'));
    }

    public function show($entryCode)
    {
        $vote = Vote::where('entryCode', $entryCode)->first();
        $comments = $vote->comments()->latest()->get();
        $questions = $vote->questions;
        $questionArray = array();
        foreach ($questions as $question) { //get the question, its choices and the corresponding selections
            $tempArray = array();
            $sum = 0;
            foreach ($question->choices as $choice) {
                $tempCount = $vote->selections()->where('question_id', $question->id)->where('choice_id', $choice->id)->count();
                $sum += $tempCount;
                array_push($tempArray, [$choice, $tempCount]);
            }
            array_push($questionArray, [$question, $tempArray, $sum]);
        }
        //dd($questions, $questionArray);

        /*$selectionArray = array();
        foreach ($questions as $question) {     //calculate the corresponding number of questions
            $tempArray = array();
            $sum = 0;
            foreach ($question->choices as $choice) {
                $tempCount = $vote->selections()->where('question_id', $question->id)->where('choice_id', $choice->id)->count();
                $sum += $tempCount;
                array_push($tempArray, $tempCount);
            }
            array_push($tempArray, $sum);
            array_push($selectionArray, $tempArray);
        }*/

        $timeBeforeNextVote = null;
        if(Auth::check()) {     //show user's last vote choice
            $userLastVote = array();
            foreach ($questions as $question) {
                break;
            }
            $userLastVoteTime = $vote->selections()->where('user_id', Auth::user()->id)->first()['created_at'];
            $timeGap = (strtotime(Carbon::now()) - strtotime($userLastVoteTime))/60;
            if( (int)$timeGap <= $vote->voteGap) {
                //time gap is bigger than vote gap now
                $timeBeforeNextVote = $vote->voteGap - (int)$timeGap;
            }
        }

        //dd($userLastVoteTime);
        return view('votes.show', compact('vote', 'comments', 'questionArray', 'timeBeforeNextVote'));
    }

    public function select()
    {
        $vote_id = request()->input('vote');
        foreach ( request()->except(['_token', 'vote']) as $key => $value) {
            if ( $key.contains('question') ) {
                $question_id = ltrim($key, 'question');
                $choice_id = ltrim($value, 'choice');
                Selection::create([
                    'user_id' => auth()->user()->id,
                    'vote_id' => $vote_id,
                    'question_id' => $question_id,
                    'choice_id' => $choice_id
                ]);
            }
        }
        return back();
    }

    public function create()
    {
        return view('votes.create');
    }

    public function store()
    {
        //dd(request());
        $this->validate(request(),[
            'vote_title' => 'required|min:2',
            'question1' => 'required'
        ]);

        dd(request()->all());
    }

    public function store1()
    {
        $post = new \App\Post;  //use App\Post;
        $post->title = request('title');
        $post->body = request('body');
        $post->save();

        return redirect('/postsindex');
    }
    public function store2()
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
