<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfolioUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('uploadable');
            $table->integer('portfolio_id')->unsigned()->index();
            $table->string('filename');
            $table->text('path');
            $table->string('type');
            $table->bigInteger('size');
            $table->boolean('approved')->default(false);
            $table->boolean('preview')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('portfolio_id')->references('id')->on('portfolios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_uploads');
    }
}
