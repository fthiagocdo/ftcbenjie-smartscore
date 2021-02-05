<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompetitorsChangeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('competitors', 'email')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->string('phone')->nullable(true)->change();
                $table->string('email')->nullable(true)->change();
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
        if(Schema::hasColumn('competitors', 'email')){
            Schema::table('competitors', function (Blueprint $table) {
                $table->string('phone')->nullable(false)->change();
                $table->string('email')->nullable(false)->change();
            });
        }
    }
}
