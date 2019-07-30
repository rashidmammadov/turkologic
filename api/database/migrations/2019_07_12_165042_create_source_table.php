<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_SOURCE_TABLE, function (Blueprint $table) {
            $table->bigIncrements(SOURCE_ID);
            $table->bigInteger(ETYMON_ID)->reference(ETYMON_ID)->on(DB_ETYMON_TABLE);
            $table->text(SAMPLE);
            $table->string(REFERENCE)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_SOURCE_TABLE);
    }
}
