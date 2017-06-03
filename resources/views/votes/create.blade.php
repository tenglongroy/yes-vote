@extends('layouts.master')

@section('content')
<div class="col-sm-8 blog-main">
    <form action="/votes/store" method="POST" id="vote_form">
        {{ csrf_field() }}

        <div class="row">
            <h2>title</h2>
            <input type="text" name="vote_title" placeholder="Title" required/>
        </div>

        <div class="row">
            <h3>description</h3>
            <textarea name="vote_description" placeholder="Description" rows="4" cols="50"></textarea>
        </div>

        <div class="row">
            <input type="checkbox" name="vote_comment" value="0"/>
            <h4>no comment</h4>
        </div>

        <div class="row">
            <input type="checkbox" name="vote_anonymous" value="1"/>
            <h4>anonymous</h4>
        </div>

        <div class="row">
            <input type="checkbox" name="vote_public" value="0"/>
            <h4>private</h4>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <input type="datetime-local" name="vote_starttime" required/>
                <h4>start time</h4>
            </div>
            <div class="col-sm-4">
                <input type="datetime-local" name="vote_endtime" required/>
                <h4>end time</h4>
            </div>
        </div>


        <div class="row">
            <h4>vote gap:</h4>
            <select name="vote_gap">
                <option value="infinite">infinite</option>
                <option value="30min">30 min</option>
                <option value="6hour">6 hours</option>
                <option value="1day">1 day</option>
                <option value="7day">7 days</option>
                <option value="1month">1 month</option>
            </select>
        </div>

        <input id="toSubmit" type="submit" class="btn btn-primary btn-lg" style="font-size: x-large;"/>

        @include('layouts.errors')
    </form>

    {{--<hr>
    <div class="form-group">
        <input id="toSubmit111" type="submit" class="btn btn-primary btn-lg" style="font-size: x-large;"/>
    </div>
    <script>
        document.getElementById('toSubmit111').addEventListener("click", function () {
            document.getElementById('vote_form').submit();
        })
    </script>--}}


    <hr>
    <button value="one more question" id="more_question_button" >one more question</button>
    <button value="one less question" id="less_question_button" >one less question</button>


    <script>
        function countInputIndex(tagName){
            return document.getElementById(tagName).childElementCount - 6;
        };

        question_index = 0;     //the value corresponds to each question's index
        var newDiv = '<div class="container" id="{@index}"><hr><h5>{@index}</h5>';
        newDiv += '<div class="row"><input type="text" name="{@index}" placeholder="question" required/></div>';
        newDiv += '<div class="row"><input type="checkbox" id="{@index}_multiple" name="{@index}_multiple" value="1" /><h5>multiple</h5></div>';
        newDiv += '<div class="row" id="{@index}_max" style="display:none" ><h6>max choice</h6><input type="number" name="{@index}_max"/></div>';
        newDiv += '<div class="row"><input type="button" id="{@index}_add" value="Add"/>';
        newDiv += '<input type="button" id="{@index}_minus" value="Minus"/></div>';
        newDiv += '<div class="row input" id="{@index}_choice1"><input type="text" name="{@index}_choice1" placeholder="{@index}_choice1" required/></div>';
        newDiv += '<div class="row input" id="{@index}_choice2"><input type="text" name="{@index}_choice2" placeholder="{@index}_choice2" required/></div></div>';


        $(function() {
            $('#more_question_button').click(function(){
                //$('#vote_form').append(newDiv.replace(/{@index}/g, "question"+(question_index+1)));
                $(newDiv.replace(/{@index}/g, "question"+(question_index+1))).insertBefore('#toSubmit');
                //event for multiple choice checkbox
                $("#question"+(question_index+1)+"_multiple").change(function(){
                    var maxName = '#'+this.name.split('_')[0]+'_max';
                    if(this.checked){
                        $(maxName).show();
                    }
                    else {
                        $(maxName).hide();
                    }
                });
                //event for add a choice
                $("#question"+(question_index+1)+"_add").click(function(){
                    var newInput = '<div class="row input" id="{@index}"><input type="text" name="{@index}" placeholder="{@index}" {@required}/></div>';
                    var idSplit = this.id.split('_')[0];
                    var count = countInputIndex(idSplit);
                    var newInputTemp = newInput.replace(/{@index}/g, idSplit+"_choice"+(count+1));

                    if(count < 2)   //if less than 3 choices, they must be required as a question requires at least 2 choices
                        newInputTemp = newInputTemp.replace("{@required}", "required");
                    else
                        newInputTemp = newInputTemp.replace("{@required}", " ");

                    $('#'+idSplit).append(newInputTemp);
                });
                //event for minus a choice
                $("#question"+(question_index+1)+"_minus").click(function(){
                    var idSplit = this.id.split('_')[0];
                    console.log('remove #'+idSplit+'_choice'+countInputIndex(idSplit))
                    $('#'+idSplit+'_choice'+countInputIndex(idSplit)).remove();
                });

                question_index++;
            });
        });
        $(function() {
            $('#less_question_button').click(function(){
                var oldDiv = '#{@index}';
                $(oldDiv.replace("{@index}", "question"+question_index)).remove();
                question_index--;
            });
        });
    </script>
</div>
















@endsection