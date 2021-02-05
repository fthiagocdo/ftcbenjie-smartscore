<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompetitionDropColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('competitions', 'quantity_competitors')){
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('quantity_competitors');
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
        if(!Schema::hasColumn('competitions', 'quantity_competitors')){
            Schema::table('competitions', function (Blueprint $table) {
                $table->integer('quantity_competitors')->nullable(false);
            });
        }
    }
}
