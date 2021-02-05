<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsersAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('judges', 'order')){
            Schema::table('judges', function (Blueprint $table) {
                $table->number('order');
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
        if(Schema::hasColumn('judges', 'order')){
            Schema::table('judge', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
}
