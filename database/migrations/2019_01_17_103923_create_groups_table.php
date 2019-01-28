<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $group = new App\Groups();
        $group->name = 'Profesores';
        $group->save();

        $group = new App\Groups();
        $group->name = 'Coordinadores';
        $group->save();

        $group = new App\Groups();
        $group->name = 'Alumnos';
        $group->save();

        $group = new App\Groups();
        $group->name = 'Ex-Alumnos';
        $group->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
