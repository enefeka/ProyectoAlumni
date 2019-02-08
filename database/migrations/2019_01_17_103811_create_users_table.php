<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->integer('phone')->nullable();
            $table->string('username');
            $table->string('birthday')->nullable();
            $table->boolean('is_registered');
            $table->integer('id_rol')->unsigned();
            $table->foreign('id_rol')
                  ->references('id')->on('roles')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->integer('id_privacity')->unsigned();
            $table->foreign('id_privacity')
                  ->references('id')->on('privacities')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->float('lon')->nullable();
            $table->float('lat')->nullable();
            $table->timestamps();
        });
        
        $user = new App\Users();
        $user->password = 'admin';
        $user->email = 'miguel_heredia_apps1ma1718@cev.com';
        $user->username = 'admin';
        $user->is_registered = '1';
        $user->id_rol = '1';
        $user->id_privacity = 1;
        $user->name = 'admin';
        $user->save();
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
