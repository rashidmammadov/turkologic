<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemanticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(DB_SEMANTICS_TABLE, function (Blueprint $table) {
            $table->bigIncrements(SEMANTIC_ID);
            $table->bigInteger(LEXEME_ID)->reference(LEXEME_ID)->on(DB_LEXEME_TABLE);
            $table->integer(TYPE)->nullable();
            $table->text(MEANING)->nullable();
            $table->text(SAMPLE)->nullable();
            $table->string(REFERENCE, 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(DB_SEMANTICS_TABLE);
    }
}
