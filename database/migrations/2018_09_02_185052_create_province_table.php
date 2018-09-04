<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvinceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('province', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id')->comment('国家id');
            $table->unsignedInteger('region_id')->comment('地理大区id')->nullable();
            $table->string('name')->comment('名称');
            $table->string('code')->comment('代码')->nullable();
            $table->string('short_name')->comment('简称')->nullable();
            $table->char('initial')->comment('首字母大写')->nullable();
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
        Schema::dropIfExists('province');
    }
}
