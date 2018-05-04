<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id');
            $table->integer('version_id');
            $table->text('body');
            $table->decimal('lat')->nullable();
            $table->decimal('lng')->nullable();
            $table->string('l_obj')->nullable();
            $table->string('file_path')->nullable();
            $table->string('type')->default('Mark');
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
        Schema::dropIfExists('marks');
    }
}
