@extends('layouts.master')
{{--'currentUser', 'votes', 'selections'--}}

@section('content')
<div class="col-sm-8 blog-main container">


    <div class="row" id="user_email" style="display: inline;">
        <mark>Email:   </mark>
        <a class="h4">{{ $user->email }}</a>
    </div>
    <div class="field-container emailAddress">
        <label for="emailAddress">
            Email Address
        </label>
        <input name="email1" type="text" id="emailAddress" value="roy1990226@gmail.com" />
    </div>

    <div class="row" id="user_name" style="display: inline;">
        <mark>Name:   </mark>
        <a class="h3">{{ auth()->user()->name }}</a></div>

    <div class="formButtons field-container changePassword">
        <form action="/users/change" method="POST" >
            {{ csrf_field() }}
            <div class="row form-group" style="display: inline">
                <div class="col-xs-3">
                    <button id="change_password" class="btn btn-primary primary-action" type="button">
                    Change Password</button>
                </div>
                <div class="col-xs-offset-1 col-xs-3">
                    <input id="old_password" type="password" name="old_password" placeholder="Old Password" style="display: none">
                </div>
            </div>
            <div class="row form-group" style="display: inline">
                <div class="col-xs-3">
                    <input id="new_password" type="password" name="password" placeholder="New Password" style="display: none">
                </div>
                <div class="col-xs-offset-1 col-xs-3">
                    <input id="confirm_password" type="password" name="confirm_password" placeholder="Confirm Password" style="display: none">
                </div>
                <div class="col-xs-offset-1 col-xs-3">
                    <input id="submit_password" class="btn btn-danger active" type="submit" value="Change Password" style="display: none">
                </div>
            </div>
        </form>
    </div>
    <script>
        $('#change_password').click(function () {
            $('#old_password').show();
            $('#new_password').show();
            $('#confirm_password').show();
            $('#submit_password').show();
        })

        var old_password = document.getElementById("old_password");
        var new_password = document.getElementById("new_password");
        var confirm_password = document.getElementById("confirm_password");

        $('#submit_password').click(function (event) {
            if($('#old_password').val().length == 0){
                $('#old_password').get(0).setCustomValidity("Old password cannot be empty.");
                old_password.setCustomValidity("Old password cannot be empty.");
            }
            else if($('#new_password').val().length < 8){
                $('#new_password').get(0).setCustomValidity("New password must be at least 8 characters.");
            }
            else if($('#confirm_password').val() != $('#new_password').val()){
                $('#confirm_password').get(0).setCustomValidity("New password doesn't match.");
            }
            else{
                return true;
            }
            event.preventDefault();
        })

        /*var old_password = document.getElementById("old_password");
        var new_password = document.getElementById("new_password");
        var confirm_password = document.getElementById("confirm_password");

        function validatePassword(){
            if(old_password.value.length == 0){
                old_password.setCustomValidity("Old password cannot be empty.");
            }
            else if(new_password.value != confirm_password.value) {
                //show the hint when fail validity
                confirm_password.setCustomValidity("New password doesn't match.");
            }
            else if(new_password.value.length == 0) {
                new_password.setCustomValidity("Passwords cannot be empty.")
            }
            else {
                confirm_password.setCustomValidity('');
            }
        }
        document.getElementById("submit_password").onclick = validatePassword;*/
    </script>
    {{--<div class="row sidebar-module btn btn-warning btn-lg" id="user_password" data-toggle="modal" data-target="#changePassword">
        Change Password
    </div>--}}
    <hr>

    @if(count($votes))
    <div class="row" id="user_votes">
        <h3>User created.</h3>
        <ul class="list-group w-100">
            @foreach($votes as $vote)
                <li class="list-group-item list-group-item-action list-group-item-info">
                    <div class="d-flex justify-content-between">
                    <h4 class="mb-1">{{ $vote->title }}</h4>
                        <em>{{ $vote->description }}</em>
                    <small>{{ $vote->created_at->toDateString() /*format('Y-m-d')*/ }}</small>
                    {{--<p class="mb-1">{{ $vote->description }}</p>--}}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(count($selections))
    <div class="row" id="user_selections">
        <h3>User voted.</h3>
        <ul class="list-group w-100">
            @foreach($selections as $selection)
                <li class="list-group-item list-group-item-action list-group-item-warning">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-1">{{ $vote->title }}</h4>
                        {{--<em>{{ $vote->description }}</em>--}}
                        <small>{{ $vote->created_at->toDateString() /*format('Y-m-d')*/ }}</small>
                        {{--<p class="mb-1">{{ $vote->description }}</p>--}}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>


{{--modal--}}
<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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










@endsection