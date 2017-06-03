<?php

namespace App\Http\Controllers;

use App\Choice;
use App\Comment;
use App\Question;
use App\Selection;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

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
        //$votedUsers = Selection::where('vote_id', $vote->id)->latest()->select('user_id')->groupBy('user_id')->get();
        $votedUsers = $vote->selections()->latest()->select('user_id')->groupBy('user_id')->get();
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
        return view('votes.show', compact('vote', 'comments', 'questionArray', 'timeBeforeNextVote', 'votedUsers'));
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

    public function store(Request $request)
    {
        //dd(request());
        $this->validate($request,[
            'vote_title' => 'required|min:2',
            'question1' => 'required|min:2'
        ]);

        //dd(request()->all());

        $vote_title = $request->input('vote_title');
        //$description = request().hasKey('vote_description')? request('vote_description'):' ';
        $description = $request->input('vote_description', ' ');
        //$vote_comment = request().hasKey('vote_comment')? request('vote_comment'):true;
        $vote_comment = $request->input('vote_comment', true);
        //$vote_anonymous = request().hasKey('vote_anonymous')? request('vote_anonymous'):false;
        $vote_anonymous = $request->input('vote_anonymous', false);
        //$vote_public = request().hasKey('vote_public')? request('vote_public'):true;
        $vote_public = $request->input('vote_public', true);
        $vote_starttime = $request->exists('vote_starttime')? Carbon::parse($request->input('vote_starttime')):Carbon::now();
        $vote_endtime = $request->exists('vote_endtime')? Carbon::parse($request->input('vote_endtime')):Carbon::now();
        $vote_gap = $request->input('vote_gap');
        if($vote_gap.equalToIgnoringCase('infinite'))
            $vote_gap = 1000000;
        $entryCode = bcrypt(auth()->user()->id);
        $entryCode = str_replace('/', 'R', $entryCode);
        $newVote = Vote::create([
            'user_id' => auth()->user()->id,
            'title' => $vote_title,
            'description' => $description,
            'isComment' => $vote_comment,
            'isAnonymous' => $vote_anonymous,
            'isPublic' => $vote_public,
            'startTime' => $vote_starttime,
            'endTime' => $vote_endtime,
            'voteGap' => $vote_gap,
            'entryCode' => $entryCode
        ]);

        error_log(gettype($request->except('_token'))); //array

        //question-vote dict [question-name(created): [choice-body, choice-body..], ...]
        /*foreach ( $request->except([
            '_token', 'vote_title', 'vote_description', 'vote_comment', 'vote_anonymous', 'vote_public', 'vote_starttime', 'vote_endtime', 'vote_gap', 'infinite'
        ]) as $key => $value) {
            //error_log($key);error_log($value);
            if(strpos($key,'question')==0 && strpos($key, '_')==false){

            }
        }*/

        $question_array1 = $request->except([
            '_token', 'vote_title', 'vote_description', 'vote_comment', 'vote_anonymous', 'vote_public', 'vote_starttime', 'vote_endtime', 'vote_gap', 'infinite'
        ]);
        $flagQuestion = '';
        $flagMultiple = false;
        $currentQuestion = null;
        foreach($question_array1 as $key => $value){
            if(strpos($key,'question')==0
                && strpos($key, '_')==false){   //'question1'
                $flagQuestion = $value;
                $currentQuestion = null;
            }
            //'question1_multiple' exists
            elseif ($flagQuestion !== '' && strpos($key, '_multiple') !== false){
                $flagMultiple = true;
            }
            //'question1_max'
            elseif (strpos($key, '_max') !== false){
                $currentQuestion = Question::create([
                    'vote_id' => $newVote->id,
                    'description' => $flagQuestion,
                    'isMultiple' => $flagMultiple? 1:0,
                    'maxChoice' => $value==null? 1:$value
                ]);
                //re-init
                $flagQuestion = '';
                $flagMultiple = false;
            }
            //'question1_choice1'
            elseif (strpos($key, '_choice') !== false){
                Choice::create([
                    'question_id' => $currentQuestion->id,
                    'body' => $value
                ]);
            }
        }

        return redirect('/votes/'.$entryCode);


        /*$question_array = array($request->all());
        for($index = 0; $index < count($question_array); $index++){
            error_log($question_array[$index][0]);error_log($question_array[$index]->value);
            if(strpos($question_array[$index][0],'question')==0
                && strpos($question_array[$index][0], '_')==false){

            }
        }*/
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
