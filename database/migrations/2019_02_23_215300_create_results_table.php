<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->string('vote_id', 255);
            $table->string('item', 255);
            $table->integer('count');
            $table->timestamp('created_at');
            $table->primary(['vote_id', 'item', 'count', 'created_at']);
            $table->index(['vote_id']);
            $table->index(['item']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
