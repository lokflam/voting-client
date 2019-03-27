<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('job', 255);
            $table->string('data', 255);
            $table->string('intervel');
            $table->timestamp('created_at');
            $table->index(['job']);
            $table->index(['data']);
            $table->index(['intervel']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron');
    }
}
