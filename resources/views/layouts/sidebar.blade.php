<div class="col-sm-4 offset-sm-0 blog-sidebar">

    @if(! Auth::check() )
    <div class="sidebar-module col-lg-12" style="height: 300px; background-color: lightblue;">
        <h4>Want to start a vote? Login!</h4>
        <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-3 control-label">Email</label>

                        <div class="col-md-9">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-3 control-label">Password</label>

                        <div class="col-md-9">
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>

                            <div class="col-sm-8">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                            </div>
                        </div>
                    </div>

                    {{--<div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Login
                            </button>

                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                        </div>
                    </div>--}}
                </form>
            </div>
        </div>
    </div>
    @else
        {{--<button type="submit" formaction="/votes/create" class="sidebar-module btn btn-success btn-lg" style="font-size: x-large;">
            Create a vote</button>--}}
        <div>
            <a class="sidebar-module btn btn-success btn-lg" style="font-size: x-large;" href="{{ route('vote_create') }}">
                Create a vote</a>
        </div>
        {{--<div class="sidebar-module btn btn-success btn-lg" data-toggle="modal" data-target="#myPassword">
            Create a vote
        </div>--}}
    @endif


    <div class="sidebar-module sidebar-module-inset">
        <h4>About</h4>
        <p>Etiam porta <em>sem malesuada magna</em> mollis euismod. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur.</p>
    </div>

    <div class="sidebar-module">
        <h4>Archives</h4>
        <ol class="list-unstyled">
            @foreach($archives as $stats)
                <li>
                    <a href="/index?month={{ $stats['month'] }}&year={{ $stats['year'] }}">
                        {{ $stats['month'].' '.$stats['year'] }}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>

    {{--<div class="sidebar-module">
        <h4>Tags</h4>
        <ol class="list-unstyled">
            @foreach($tags as $tag)
                <li>
                    <a href="/posts/tags/{{ $tag }}">
                        {{ $tag }}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>--}}

    <div class="sidebar-module">
        <h4>Elsewhere</h4>
        <ol class="list-unstyled">
            <li><a href="http://tenglongroy.pythonanywhere.com">Activity Register</a> </li>
            <li><a href="https://github.com/tenglongroy">My GitHub</a></li>
            <li><a href="https://www.linkedin.com/in/tenglongroy/">My LinkedIn</a></li>
            <li><a href="https://www.facebook.com/tenglongroy">My Facebook</a></li>
        </ol>
    </div>
</div><!-- /.blog-sidebar -->




{{--modal--}}
<div class="modal fade" id="myPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel" align="center">choose how many question</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <form class="form-horizontal"  action="/votes/create" method="POST" >
                            <div class="form-group">
                                <label class="control-label inline", for='new_password'>password</label>
                                <input type="password" class="form-control" name="new_password" id="new_password">

                                <label class="control-label inline", for='confirm_password'>repeat</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password">

                            </div>
                            <button type="submit"  class ="btn btn-primary" >Update</button>
                            <input type="reset">
                        </form> <!-- end form -->
                    </div>
                </div>
            </div> <!-- modal body -->
        </div> <!-- modal content -->
    </div> <!-- modal dialog -->
</div>