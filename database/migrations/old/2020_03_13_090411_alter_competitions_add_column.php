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
        if(!Schema::hasColumn('competitions', 'finished')){
            Schema::table('competitions', function (Blueprint $table) {
                $table->boolean('finished')->default(false);
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
        if(Schema::hasColumn('competitions', 'finished')){
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('finished');
            });
        }
    }
}
