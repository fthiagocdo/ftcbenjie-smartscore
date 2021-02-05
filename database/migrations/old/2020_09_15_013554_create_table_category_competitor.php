<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCategoryCompetitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('category_competitor')) {
            Schema::create('category_competitor', function (Blueprint $table) {
                $table->integer('category_id')->unsigned();
                $table->integer('competitor_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                $table->foreign('competitor_id')->references('id')->on('competitors')->onDelete('cascade');

                $table->primary(['category_id', 'competitor_id']);
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
        Schema::dropIfExists('category_competitor');
    }
}
