<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $type = new App\Types();
        $type->name = 'Eventos';
        $type->save();

        $type = new App\Types();
        $type->name = 'Ofertas trabajo';
        $type->save();

        $type = new App\Types();
        $type->name = 'Notificaciones';
        $type->save();

        $type = new App\Types();
        $type->name = 'Noticias';
        $type->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types');
    }
}
