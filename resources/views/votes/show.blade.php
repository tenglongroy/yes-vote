@extends('layouts.master')
{{--'vote', 'comments', 'questionArray', 'userLastVoteTime'--}}
@section('content')
    <div class="col-sm-8 blog-main">
        <div class="row">
            <h1>{{ $vote->title }}</h1>
        </div>
        <div class="row">
            <h3>{{ $vote->description }}</h3>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h5>{{ $vote->user->name }} </h5>
            </div>
            <div class="col-sm-offset-1 col-sm-3">
                <h6>&nbsp;&nbsp; on {{ $vote->created_at->toFormattedDateString() }}</h6>
            </div>
        </div>
        <div class="row">
            @if($vote->startTime > \Carbon\Carbon::now())
                <h5>begins  in {{ $vote->startTime->diffForHumans() }}</h5>
            @elseif($vote->endTime > \Carbon\Carbon::now())
                <h5>ends in {{ $vote->endTime->diffForHumans() }}</h5>
            @else
                <h5>already ended at {{ $vote->endTime }}</h5>
            @endif
        </div>







        {{--show votes--}}

        <div class="row">
            <form action="/votes/select" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="vote" value="{{ $vote->id }}">
                <ul>
                @foreach( $questionArray as $item )
                    <li>
                        <div class="container" id="votediv{{ $item[0]->id }}">
                            <h4>{{ $item[0]->description }}</h4>
                            <ul>
                            {{--<form id="vote{{ $item[0]->id }}" action="/votes/select" method="POST">--}}
                                @if( $item[0]->isMultiple)
                                    <a>max choice {{ $item[0]->maxChoice }}</a>
                                    @foreach( $item[1] as $choice)
                                    <li>
                                        <div class="col-md-7">
                                        <input type="checkbox" name="question{{ $choice[0]->question_id }}" value="choice{{ $choice[0]->id }}" class="list-inline">
                                            <a class="list-inline">{{ $choice[0]->body }}</a>
                                        </div>
                                        <div class="col-md-5 list-inline">{{ $choice[1]/$item[2] }}</div>
                                    </li>
                                    @endforeach
                                @else
                                    @foreach( $item[1] as $choice)
                                    <div class="row">
                                    <li>
                                        <div class="col-lg-7">
                                            <input type="radio" name="question{{ $choice[0]->question_id }}" value="choice{{ $choice[0]->id }}" class="list-inline">
                                            <a class="list-inline">{{ $choice[0]->body }}</a>
                                        </div>
                                        <div class="col-lg-4 list-inline">{{ $choice[1]/$item[2] }}</div>
                                    </div>
                                    </li>
                                    @endforeach
                                @endif
                            {{--</form>--}}
                            </ul>
                        </div>
                    </li>
                @endforeach
                </ul>

                <div class="row" id="form_submit_button">
                    <button type="submit" class="btn btn-primary" onclick="submitForms()" id="asdfbutton">
                        Submit your vote!
                    </button>
                    {{--<input type="button" value="Submit your vote!" onclick="submitForms()" />--}}
                </div>
                {{--show button or hide    --}}
                @if( $timeBeforeNextVote)
                    @if( $vote->voteGap < 999999999)
                        <div class="row">
                            {{ $timeBeforeNextVote }}min before you can vote again
                        </div>
                    @else
                        <div class="row">
                            You've voted.
                        </div>
                    @endif
                    <script>
                        document.getElementById("form_submit_button").style.display = "show";
                    </script>
                @else
                    <script>
                        document.getElementById("form_submit_button").style.display = "show";
                    </script>
                @endif
                <script>
                    function submitForms(){
                        alert("hello");
                    }
                </script>

            </form>
        </div>

        <hr>

        @include('votes.createComments')

        <hr>

        @include('votes.showComments')
    </div>






@endsection