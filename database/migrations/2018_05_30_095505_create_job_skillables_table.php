<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobSkillablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_skillables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id')->unsigned()->index();
            $table->integer('level_id')->unsigned()->index()->nullable();
            $table->morphs('skillable');
            $table->string('details')->nullable();
            $table->timestamps();

            $table->foreign('job_id')->references('id')->on('job')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_skillables');
    }
}
