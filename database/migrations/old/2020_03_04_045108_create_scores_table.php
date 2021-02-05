<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('scores')) {
            Schema::create('scores', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('competitor_id')->unsigned();
                $table->foreign('competitor_id')->references('id')->on('competitors')->onDelete('cascade');
                $table->integer('judge_id')->unsigned();
                $table->foreign('judge_id')->references('id')->on('judges')->onDelete('cascade');
                $table->decimal('score')->nullable(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
