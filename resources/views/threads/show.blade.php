@extends('layouts.master')
{{--'thread', 'comments', 'voteArray', 'userLastVoteTime'--}}
@section('content')
    <div class="col-sm-8 blog-main">
        <div class="row">
            <h1>{{ $thread->title }}</h1>
        </div>
        <div class="row">
            <h3>{{ $thread->description }}</h3>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h5>{{ $thread->user->name }} </h5>
            </div>
            <div class="col-sm-offset-1 col-sm-3">
                <h6>&nbsp;&nbsp; on {{ $thread->created_at->toFormattedDateString() }}</h6>
            </div>
        </div>
        <div class="row">
            @if($thread->startTime > \Carbon\Carbon::now())
                <h5>begins  in {{ $thread->startTime->diffForHumans() }}</h5>
            @elseif($thread->endTime > \Carbon\Carbon::now())
                <h5>ends in {{ $thread->endTime->diffForHumans() }}</h5>
            @else
                <h5>already ended at {{ $thread->endTime }}</h5>
            @endif
        </div>







        {{--show votes--}}

        <div class="row">
            <ul>
            @foreach( $voteArray as $item )
                <li>
                    <div class="container" id="votediv{{ $item[0]->id }}">
                        <h4>{{ $item[0]->question }}</h4>
                            <ul>
                            <form id="vote{{ $item[0]->id }}" action="/threads" method="POST">
                                {{ csrf_field() }}
                                @foreach( $item[1] as $choice)
                                @if( $item[0]->isMultiple)
                                    <li>
                                        <input type="checkbox" name="choice{{ $item[0]->id }}" value="1">
                                        {{ $choice[0]->body }}   {{ $choice[1]/$item[2] }}
                                    </li>
                                @else
                                    <div class="row">
                                    <li>
                                        <div class="col-md-7">
                                            <input type="radio" name="{{ $item[0]->id }}" value="1" class="radio-inline">
                                            <a class="list-inline">{{ $choice[0]->body }}</a>
                                        </div>
                                        <div class="col-md-5 list-inline">{{ $choice[1]/$item[2] }}</div>
                                    </div>
                                    </li>
                                @endif
                                @endforeach
                            </form>
                            </ul>
                        {{--<div class="col-sm-4 col-sm-offset-1">
                            <h4>voting result</h4>
                            <ul>
                                @foreach( $item[1] as $choice)
                                    <li>{{ $choice[1]/$item[2] }}</li>
                                    @endforeach
                            </ul>
                        </div>--}}
                    </div>
                </li>
            @endforeach
            </ul>

            @if( $timeBeforeNextVote)
                @if( $thread->voteGap < 999999999)
                    <div class="row">
                        {{ $timeBeforeNextVote }}min before you can vote again
                    </div>
                @else
                    <div class="row">
                        You've voted.
                    </div>
                @endif
                <script>
                    document.getElementById("form_submit_button").style.display = "none";
                </script>
            @endif
            <div class="row" id="form_submit_button">
                <input type="button" value="Submit your vote!" onclick="submitForms()" />
            </div>
            <script>
                //check if the selection matches the MAX choice number

                submitForms = function(){
                    @foreach($voteArray as $item)
                        document.getElementById("vote{{ $item[0]->id }}").submit();
                    @endforeach
                }
            </script>
        </div>

        <hr>

        {{--<div class="row">
            <h4>Write your comment.</h4>
        </div>

        <div class="row">
            <form method="POST" action="/threads/{thread}/comment">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="text" class="form-control" id="body" name="body" placeholder="body">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Publish</button>
                </div>

                @include('layouts.errors')
            </form>
        </div>--}}

        @include('threads.createComments')

        <hr>

        {{--<div class="row">
            @if( count( $comments ) )
                <ul>
                @foreach( $comments as $comment)
                    <li>
                        <h4>{{ $comment->body }}</h4>
                        by <b>{{ $comment->user->name }}</b> on {{ $comment->created_at->diffForHumans() }}
                    </li>
                @endforeach
                </ul>
            @endif
        </div>--}}

        @include('threads.showComments')
    </div>






@endsection