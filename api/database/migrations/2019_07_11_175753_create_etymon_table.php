<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtymonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_ETYMON_TABLE, function (Blueprint $table) {
            $table->bigIncrements(ETYMON_ID);
            $table->integer(LANGUAGE_ID)->nullable()->reference(LANGUAGE_ID)->on(DB_LANGUAGE_TABLE);
            $table->string(WORD, 50)->nullable();
            $table->string(PRONUNCIATION, 50)->nullable();
            $table->integer(TYPE)->nullable();
            $table->text(DESCRIPTION)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_ETYMON_TABLE);
    }
}
