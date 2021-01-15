<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationformTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluationform', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluator');
            $table->foreign('evaluator')->references('id')->on('employee');
            $table->foreignId('employee');
            $table->foreign('employee')->references('id')->on('employee');
            $table->date('start_period');
            $table->date('end_period');
            $table->date('evaluation_date')->nullable();
            $table->integer('isSubmitted');
            $table->integer('isArchived');
            $table->date('archived_date')->nullable();
            $table->string('comment')->nullable();
            $table->float('final_rate')->nullable();
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
        Schema::dropIfExists('evaluationform');
    }
}
