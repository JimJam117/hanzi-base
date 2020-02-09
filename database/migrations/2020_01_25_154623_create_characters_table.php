<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('char')->unique();
            $table->string('simp_char')->nullable();
            $table->string('trad_char')->nullable();
            $table->integer('freq')->nullable();
            $table->integer('stroke_count')->nullable();
            $table->string('radical')->nullable();
            $table->string('simp_radical')->nullable();
            $table->string('heisig_keyword')->nullable();
            $table->integer('heisig_number')->nullable();
            $table->string('pinyin')->nullable();
            $table->string('pinyin_normalised')->nullable();
            $table->string('translations')->nullable();
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
        Schema::dropIfExists('characters');
    }
}
