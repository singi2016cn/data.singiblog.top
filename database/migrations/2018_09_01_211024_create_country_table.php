<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('continent_id')->comment('大洲')->default(0);
            $table->string('short_name')->comment('名称');
            $table->string('full_name')->comment('全称')->nullable();
            $table->string('short_name_en')->comment('英文简称')->nullable();
            $table->string('code')->unique()->comment('代码')->nullable();
            $table->string('state_system')->comment('国家体制')->default(0);
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
        Schema::dropIfExists('country');
    }
}
