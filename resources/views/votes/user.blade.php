@extends('layouts.master')
{{--'currentUser', 'votes', 'selections'--}}

@section('content')
<div class="col-sm-8 blog-main container">

    @include('layouts.errors')

    @if( empty($user->activated_at) )
        <div class="row">
            <a>Click button to resend activation.</a>
            @if($user->send_time == null || Carbon\Carbon::now()->diffInMinutes(Carbon\Carbon::parse($user->send_time)) >= 60)
                <a class="btn btn-warning active" href="/account/reactivate">Resend</a>
            @else
                <a class="btn btn-warning disabled">{{ 60- Carbon\Carbon::now()->diffInMinutes(Carbon\Carbon::parse($user->send_time)) }}min to resend</a>
            @endif
        </div>
    @endif

    <div class="row field-container emailAddress" id="user_email" style="display: inline;">
        <a>Email:   </a>
        <a class="h4" ><label for="emailAddress">{{ $user->email }}</label></a>
    </div>

    <div class="row" id="user_name" style="display: inline;">
        <div class="col-sm-7" style="float: left">
            <a>Name:   </a>
            <a id="name_show" contentEditable="true" class="h3">{{ auth()->user()->name }}</a>
            <input id="name_edit" style="display: none; font-size:20px" type="text">
        </div>
        <div class="col-sm-offset-1 col-sm-3">
            <form id="name_change_form" action="/users/changeName" method="POST">
                {{ csrf_field() }}
                <input type="hidden" id="name_update_ipt" name="name_update_ipt" value=" ">
                <button id="name_update_btn" style="display: none;">Update Name</button>
            </form>
        </div>
    </div>

    <div class="formButtons field-container changePassword">
        <form action="/users/changePassword" method="POST" >
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
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" style="display: none">
                </div>
                <div class="col-xs-offset-1 col-xs-3">
                    <input id="submit_password" class="btn btn-danger active" type="submit" value="Update" style="display: none">
                </div>
            </div>
        </form>
    </div>
    <script>
        function name_click(){
            $('#name_show').hide();
            $('#name_edit').show();
            $('#name_edit').focus();
            $('#name_edit').val($('#name_show').html());
            $('#name_update_btn').show();
        }
        function name_blur(){
            $('#name_show').show();
            $('#name_edit').hide();
            $('#name_update_btn').hide();
        }
        //https://stackoverflow.com/questions/10652852/jquery-fire-click-before-blur-event
        $("#name_update_btn").on('mousedown', function () {
            if(confirm('Update name?')){
                $('#name_update_ipt').val($('#name_edit').val());
                document.getElementById('name_change_form').submit();
            }
        });
        $("#name_show").click(name_click);
        $("#name_edit").blur(name_blur);

        /*https://stackoverflow.com/questions/2441565/how-do-i-make-a-div-element-editable-like-a-textarea-when-i-click-it
        function divClicked() {
            var divHtml = $(this).html();
            var editableText = $("<textarea />");
            editableText.val(divHtml);
            $(this).replaceWith(editableText);
            editableText.focus();
            // setup the blur event for this new textarea
            editableText.blur(editableTextBlurred);
        };
        function editableTextBlurred() {
            var html = $(this).val();
            var viewableText = $("<div>");
            viewableText.html(html);
            $(this).replaceWith(viewableText);
            // setup the click event for this new div
            $(viewableText).click(divClicked);
        };
        $("#name_editable").click(divClicked);
        $("#name_editable").blur(editableTextBlurred);*/

        $('#change_password').click(function () {
            $('#old_password').show();
            $('#new_password').show();
            $('#password_confirmation').show();
            $('#submit_password').show();
        })

        $('#submit_password').click(function () {
            if($('#old_password').val().length == 0){
                alert('Old password cannot be empty.');
                $('#old_password').get(0).setCustomValidity("Old password cannot be empty.");
            }
            else if($('#new_password').val().length < 8){
                alert('New password must be at least 8 characters.');
                $('#new_password').get(0).setCustomValidity("New password must be at least 8 characters.");
            }
            else if($('#password_confirmation').val() != $('#new_password').val()){
                alert("New passwords doesn't match.");
                $('#password_confirmation').get(0).setCustomValidity("New password doesn't match.");
            }
            else{
                //alert('return true');
                return true;
            }
            return false;
        })
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
                <a href={{ "/votes/".$vote->entryCode }} >
                <li class="list-group-item list-group-item-action list-group-item-info">
                    <div class="d-flex justify-content-between">
                    <h4 class="mb-1">{{ $vote->title }}</h4>
                        <em>{{ $vote->description }}</em>
                    <small>{{ $vote->created_at->toDateString() /*format('Y-m-d')*/ }}</small>
                    {{--<p class="mb-1">{{ $vote->description }}</p>--}}
                    </div>
                </li></a>
            @endforeach
        </ul>
    </div>
    @endif

    @if(count($selections))
    <div class="row" id="user_selections">
        <h3>User voted.</h3>
        <ul class="list-group w-100">
            @foreach($selections as $selection)
                <a href={{ "/votes/".$selection->entryCode }} />
                <li class="list-group-item list-group-item-action list-group-item-warning">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-1">{{ $selection->title }}</h4>
                        {{--<em>{{ $vote->description }}</em>--}}
                        <small>{{ Carbon\Carbon::parse($selection->select_time)->toDateString() /*format('Y-m-d')*/ }}</small>
                        {{--<p class="mb-1">{{ $vote->description }}</p>--}}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>








@endsection