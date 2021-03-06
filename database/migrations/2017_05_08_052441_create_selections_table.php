<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectionsTable extends Migration
{
    /**
     * Fact table
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('vote_id');
            $table->integer('question_id');
            $table->integer('choice_id');
            $table->timestamps();
            //$table->primary(['user_id', 'vote_id', 'question_id', 'choice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selections');
    }
}
