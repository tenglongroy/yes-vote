@extends('layouts.master')

@section('content')

<div class="col-sm-8 blog-main">
    @foreach( $threads as $thread )
        @include('threads.thread')
    @endforeach
</div>



    {{--<div class="col-sm-8 blog-main">

        @foreach( $threads as $thread )
            @include('threads.thread')
        @endforeach


        <nav class="blog-pagination">
            <a class="btn btn-outline-primary" href="#">Older</a>
            <a class="btn btn-outline-secondary disabled" href="#">Newer</a>
        </nav>

        @if(auth()->check())
            @include('posts.create_below')
        @endif

    </div>--}}
@endsection