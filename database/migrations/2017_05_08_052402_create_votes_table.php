<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->text('description');
            $table->boolean('isComment')->default(true);
            $table->boolean('isAnonymous')->default(false);
            $table->boolean('isPublic')->default(true);
            $table->timestamp('startTime')->nullable();
            $table->timestamp('endTime')->nullable();
            $table->integer('voteGap')->default(43200); //1 month
            $table->string('entryCode')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
