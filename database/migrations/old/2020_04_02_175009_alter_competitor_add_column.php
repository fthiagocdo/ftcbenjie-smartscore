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
        if(!Schema::hasColumn('competitors', 'total_score')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->decimal('total_score')->default(0);
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
        if(Schema::hasColumn('competitors', 'total_score')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->dropColumn('total_score');
            });
        }
    }
}
