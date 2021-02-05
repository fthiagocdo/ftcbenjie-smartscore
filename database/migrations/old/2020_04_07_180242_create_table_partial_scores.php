<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePartialScores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('partial_scores')) {
            Schema::create('partial_total_scores', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('competitor_id')->unsigned();
                $table->foreign('competitor_id')->references('id')->on('competitors')->onDelete('cascade');
                $table->integer('lap_number')->unsigned();
                $table->decimal('score');
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
        Schema::dropIfExists('partial_total_scores');
    }
}
