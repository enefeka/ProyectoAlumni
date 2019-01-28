<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->timestamps();
        });

        $role = new App\Roles();
        $role->id = 1;
        $role->type = 'admin';
        $role->save();
        
        $role = new App\Roles();
        $role->id = 2;
        $role->type = 'coordinador';
        $role->save();
                
        $role = new App\Roles();
        $role->id = 3;
        $role->type = 'profesor';
        $role->save();

        $role = new App\Roles();
        $role->id = 4;
        $role->type = 'alumno';
        $role->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
