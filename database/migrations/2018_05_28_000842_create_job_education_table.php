<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_education', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('education_id')->unsigned()->index();
            $table->integer('job_id')->unsigned()->index();
            $table->string('details');
            $table->timestamps();

            $table->foreign('education_id')->references('id')->on('education')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('job')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_education');
    }
}
