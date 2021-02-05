<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJudgesAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('judges', 'photo')){
            Schema::table('judges', function (Blueprint $table) {
                $table->string('photo')->nullable(false);
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
        if(Schema::hasColumn('judges', 'photo')){
            Schema::table('judges', function (Blueprint $table) {
                $table->dropColumn('photo');
            });
        }
    }
}
