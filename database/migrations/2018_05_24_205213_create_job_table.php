<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->index();
            $table->integer('area_id')->unsigned()->index();
            $table->string('identifier')->unique();
            $table->string('title');
            $table->string('slug', 250)->unique()->index();
            $table->string('overview_short', 300);
            $table->text('overview');
            $table->integer('applicants')->default(1);
            $table->enum('type', ['full-time', 'part-time'])->nullable();
            $table->boolean('on_location')->default(true);
            $table->decimal('salary_min', 8, 2);
            $table->decimal('salary_max', 8, 2);
            $table->boolean('live')->default(false);
            $table->boolean('approved')->default(false);
            $table->boolean('finished')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job');
    }
}
