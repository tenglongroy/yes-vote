<div class="card">
    <h3 class="card-header">{{ $thread->title }}</h3>
    <div class="card-block">
        <h4 class="card-title">{{ $thread->description }}</h4>
        <h6 class="card-subtitle mb-2 text-muted">
            {{ $thread->user->name }} on
            {{ $thread->created_at->toFormattedDateString() }}
        </h6>
        <a href="/threads/{{ $thread->id }}" class="btn btn-primary">Go to Vote</a>
    </div>
</div>




{{--
<div class=" blog-post">
    <h2 class="blog-post-title">
        <a href="/posts/{{ $thread->id }}">
            {{ $thread->title }}
        </a>
    </h2>
    <p class="blog-post-meta">
        {{ $thread->user->name }} on
        {{ $thread->created_at->toFormattedDateString() }}
    </p>
    {{ $thread->description }}
</div>
--}}