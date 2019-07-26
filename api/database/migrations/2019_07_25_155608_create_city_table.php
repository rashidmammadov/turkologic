<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_CITY_TABLE, function (Blueprint $table) {
            $table->increments(CITY_ID);
            $table->string(CITY_NAME);
            $table->double(LATITUDE);
            $table->double(LONGITUDE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_CITY_TABLE);
    }
}
