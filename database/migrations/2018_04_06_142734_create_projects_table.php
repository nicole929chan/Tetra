<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        Schema::create('project_user', function (Blueprint $table) {
            $table->integer('project_id');
            $table->integer('user_id');
            $table->timestamps();

            $table->primary(['project_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_user');
    }
}
