<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompetitorsAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('competitors', 'sponsors')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->string('sponsors')->nullable(false);
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
        if(Schema::hasColumn('competitors', 'sponsors')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->dropColumn('sponsors');
            });
        }
    }
}
