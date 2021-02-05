<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterScoreAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('scores', 'released')){
            Schema::table('scores', function (Blueprint $table) {
                $table->integer('lap_number')->nullable(false);
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
        if(Schema::hasColumn('scores', 'lap_number')){
            Schema::table('scores', function (Blueprint $table) {
                $table->dropColumn('lap_number');
            });
        }
    }
}
