<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLexemeTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_LEXEME_TABLE, function (Blueprint $table) {
            $table->bigIncrements(LEXEME_ID);
            $table->string(LEXEME, 50);
            $table->string(PRONUNCIATION, 50)->nullable();
            $table->string(LATIN_TEXT, 50)->nullable();
            $table->string(ALPHABET, 20)->nullable();
            $table->integer(LANGUAGE_ID)->reference(LANGUAGE_ID)->on(DB_LANGUAGE_TABLE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_LEXEME_TABLE);
    }
}
