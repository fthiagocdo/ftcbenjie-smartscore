<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompetitorAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('competitors', 'group')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->integer('group')->nullable(true);
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
        if(Schema::hasColumn('competitors', 'group')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->dropColumn('group');
            });
        }
    }
}
