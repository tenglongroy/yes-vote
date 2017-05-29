<div class="card">
    <h3 class="card-header">{{ $vote->title }}</h3>
    <div class="card-block">
        <h4 class="card-title">{{ $vote->description }}</h4>
        <h6 class="card-subtitle mb-2 text-muted">
            {{ $vote->user->name }} on
            {{ $vote->created_at->toFormattedDateString() }}
        </h6>
        <a href="/votes/{{ $vote->entryCode }}" class="btn btn-primary">Go to Vote</a>
    </div>
</div>




{{--
<div class=" blog-post">
    <h2 class="blog-post-title">
        <a href="/posts/{{ $vote->id }}">
            {{ $vote->title }}
        </a>
    </h2>
    <p class="blog-post-meta">
        {{ $vote->user->name }} on
        {{ $vote->created_at->toFormattedDateString() }}
    </p>
    {{ $vote->description }}
</div>
--}}