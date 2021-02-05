<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('competitors')) {
            Schema::create('competitors', function (Blueprint $table) {
                $table->increments('id');
                $table->string('photo');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('nickname');
                $table->string('phone');
                $table->string('email')->unique();
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
        Schema::dropIfExists('competitors');
    }
}
