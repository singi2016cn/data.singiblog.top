<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetroLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metro_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city_name')->comment('城市')->nullable();
            $table->string('name')->comment('名称');
            $table->string('code')->comment('代码')->nullable();
            $table->string('alias')->comment('别名')->nullable();
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
        Schema::dropIfExists('metro_lines');
    }
}
