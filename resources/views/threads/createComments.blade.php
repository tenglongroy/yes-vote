@if(auth()->check())
    <div class="row">
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
    </div>
@else
    <div class="row">
        login
    </div>
@endif