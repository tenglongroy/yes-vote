@if(count($thread->comments))
<div class="comments">
    <ul class="list-group">
        @foreach($thread->comments as $comment)
            <li class="list-group-item">
                <h2>{{ $comment->body }}</h2>
                by <b>{{ $comment->user->name }}</b> on {{ $comment->created_at->diffForHumans() }}
            </li>
        @endforeach
    </ul>
</div>
    @endif