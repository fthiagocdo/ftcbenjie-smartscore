<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->integer('order')->unsigned();
                $table->integer('quantity_competitors')->unsigned();
                $table->boolean('ongoing')->default(false);
                $table->boolean('finished')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('competitor_group')) {
            Schema::create('competitor_group', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('competitor_id')->unsigned();
                $table->integer('group_id')->unsigned();
                $table->foreign('competitor_id')->references('id')->on('competitors')->onDelete('cascade');
                $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
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
        Schema::dropIfExists('competitor_group');
        Schema::dropIfExists('groups');
    }
}
