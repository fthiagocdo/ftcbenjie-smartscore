<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('competitions')) {
            Schema::create('competitions', function (Blueprint $table) {
                $table->increments('id');
                $table->string('competition_name')->nullable(false);
                $table->integer('quantity_laps')->nullable(false);
                $table->integer('quantity_competitors')->nullable(false);
                $table->string('score_type')->nullable(false);
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
        Schema::dropIfExists('competitions');
    }
}
