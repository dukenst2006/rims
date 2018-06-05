<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identifier')->unique();
            $table->integer('company_id')->unsigned()->index()->nullable();
            $table->integer('job_id')->unsigned()->index()->nullable();
            $table->string('buyer_email');
            $table->string('gateway_id');
            $table->decimal('sale_price', 8, 2);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('job_id')->references('id')->on('job')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_sales');
    }
}
