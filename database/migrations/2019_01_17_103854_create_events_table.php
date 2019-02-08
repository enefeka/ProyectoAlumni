<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('image')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('date')->nullable();
            $table->string('url')->nullable();
            $table->integer('id_user')->unsigned()->nullable();;
            $table->foreign('id_user')
                  ->references('id')->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
            $table->integer('id_type')->unsigned();
            $table->foreign('id_type')
                  ->references('id')->on('types')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
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
        Schema::dropIfExists('events');
    }
}
