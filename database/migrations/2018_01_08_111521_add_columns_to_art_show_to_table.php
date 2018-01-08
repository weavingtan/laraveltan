<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToArtShowToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('art_show', function (Blueprint $table) {
            $table->smallInteger('status')->index()->default(1)->comment('显示隐藏');
            $table->string('mini_route')->default('')->comment('小程序路径');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('art_show', function (Blueprint $table) {
            $table->dropColumn([ 'status','mini_route']);
        });
    }
}
