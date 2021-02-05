<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompetitionsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('competitions', 'random')){
            Schema::table('competitions', function (Blueprint $table) {
                $table->boolean('random')->default(false);
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
        if(Schema::hasColumn('competitions', 'random')){
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('random');
            });
        }
    }
}
