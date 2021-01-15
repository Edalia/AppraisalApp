<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluatatedObjectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluatated_objective', function (Blueprint $table) {
            $table->foreignId('evaluationform');
            $table->foreign('evaluationform')->references('id')->on('evaluationform');
            $table->foreignId('objective');
            $table->foreign('objective')->references('id')->on('objective');
            $table->foreignId('status');
            $table->foreign('status')->references('id')->on('status');
            $table->integer('rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluatated_objective');
    }
}
