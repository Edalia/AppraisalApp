<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjectiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objective', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignId('manager_id');
            $table->foreign('manager_id')->references('id')->on('manager');
            $table->foreignId('jobtitle');
            $table->foreign('jobtitle')->references('id')->on('jobtitle');
            $table->integer("isIndividual");
            $table->integer("isActive");
            $table->string('target');
            $table->foreignId('skill');
            $table->foreign('skill')->references('id')->on('skill');
            $table->foreignId('objective_priority');
            $table->foreign('objective_priority')->references('id')->on('objectivepriority');
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
        Schema::dropIfExists('objective');
    }
}
