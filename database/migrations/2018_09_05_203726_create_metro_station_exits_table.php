<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetroStationExitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metro_station_exits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('metro_station_id')->comment('地铁站点id')->nullable();
            $table->string('name')->comment('名称');
            $table->string('note')->comment('备注')->nullable();
            $table->unsignedInteger('has_wc')->comment('厕所1有')->default(2);
            $table->unsignedInteger('has_elevator')->comment('垂直电梯1有')->default(2);
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
        Schema::dropIfExists('metro_station_exits');
    }
}
