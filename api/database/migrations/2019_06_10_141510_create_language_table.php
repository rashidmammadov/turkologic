<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_LANGUAGE_TABLE, function (Blueprint $table) {
            $table->increments(LANGUAGE_ID);
            $table->char(NAME, 50);
            $table->char(CODE, 4)->nullable();
            $table->char(FLAG, 100)->nullable();
            $table->char(COUNTRY, 50)->nullable();
            $table->smallInteger(CENTURY)->nullable();
            $table->integer(PARENT_LANGUAGE_ID)->references(LANGUAGE_ID)->on(DB_LANGUAGE_TABLE)->nullable();
            $table->boolean(STATUS)->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_LANGUAGE_TABLE);
    }
}
