<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetroStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metro_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('metro_lines_id')->comment('地铁线路id')->nullable();
            $table->string('name')->comment('名称');
            $table->string('code')->comment('代码')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metro_stations');
    }
}
