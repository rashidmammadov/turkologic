<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_COUNTRY_TABLE, function (Blueprint $table) {
            $table->increments(COUNTRY_ID);
            $table->string(COUNTRY_NAME, 50);
            $table->integer(CAPITAL_ID)->nullable()->reference(CITY_ID)->on(DB_CITY_TABLE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_COUNTRY_TABLE);
    }
}
