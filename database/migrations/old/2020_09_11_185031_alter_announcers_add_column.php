<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAnnouncersAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('announcers', 'sponsors')){
            Schema::table('announcers', function (Blueprint $table) {
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
        if(Schema::hasColumn('announcers', 'sponsors')){
            Schema::table('announcers', function (Blueprint $table) {
                $table->dropColumn('sponsors');
            });
        }
    }
}
