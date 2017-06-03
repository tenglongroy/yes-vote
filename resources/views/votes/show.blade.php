@extends('layouts.master')
{{--'vote', 'comments', 'questionArray', 'userLastVoteTime'--}}
@section('content')
<div class="col-sm-8 blog-main container">
    <div class="row">
        <h1>{{ $vote->title }}</h1>
    </div>
    <div class="row">
        <div class="col-sm-9">
        <h3>{{ $vote->description }}</h3>
        </div>
        <div class="col-sm-3">
            <button class="sidebar-module btn btn-success btn-lg" data-toggle="modal" data-target="#votedUserList">
                see who voted</button>
        </div>
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
    <hr>





    {{--show votes--}}

    <div class="row" id="form_body">
        <form action="/votes/select" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="vote" value="{{ $vote->id }}">
            @foreach( $questionArray as $item )
                <div class="panel" id="votediv{{ $item[0]->id }}">
                    <h4>{{ $item[0]->description }}</h4>
                    <ul class="list-group">
                    @if( $item[0]->isMultiple)
                        <a>max choice {{ $item[0]->maxChoice }}</a>
                        @foreach( $item[1] as $choice)
                            <label>
                            <li class="list-group-item list-group-item-action">
                                <input type="checkbox" name="question{{ $choice[0]->question_id }}" value="choice{{ $choice[0]->id }}" class="list-inline">
                                    <a class="list-inline">{{ $choice[0]->body }}</a>
                                @if($item[2] != 0)
                                    <span class="badge badge-default badge-pill">{{ $choice[1]/$item[2] }}</span>
                                @endif
                            </li>
                            </label>
                        @endforeach
                    @else
                        @foreach( $item[1] as $choice)
                            <label>
                            <li class="list-group-item list-group-item-action">
                                <input type="radio" name="question{{ $choice[0]->question_id }}" value="choice{{ $choice[0]->id }}" class="list-inline">
                                    <a class="list-inline">{{ $choice[0]->body }}</a>
                                @if($item[2] != 0)
                                    <span class="badge badge-default badge-pill">{{ $choice[1]/$item[2] }}</span>
                                @endif
                            </li>
                            </label>
                        @endforeach
                    @endif
                    </ul>
                </div>
            @endforeach

            <div class="row" id="form_submit_button">
                <button type="submit" class="btn btn-primary" onclick="submitForms()" id="asdfbutton">
                    Submit your vote!
                </button>
                {{--<input type="button" value="Submit your vote!" onclick="submitForms()" />--}}
            </div>
            {{--show button or hide    --}}
            @if( $timeBeforeNextVote)
                @if( $vote->voteGap < 999999)
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

    @if($vote->isComment)
        <hr>
        @include('votes.createComments')

    @else
        <p>The owner has closed the comment section.</p>
    @endif

    <hr>
    @include('votes.showComments')
</div>


<div class="modal fade" id="votedUserList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel" align="center">Users voted:</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>

            @if(count($votedUsers))
            <div class="modal-body list-group">
                @foreach($votedUsers as $eachUser)
                    <a class="list-group-item">{{ $eachUser->user()->first()->name }}</a>
                @endforeach
            </div> <!-- modal body -->
            @else
            <div class="modal-body alert-warning">
                <a>None</a>
            </div>
            @endif
        </div> <!-- modal content -->
    </div> <!-- modal dialog -->
</div>




@endsection