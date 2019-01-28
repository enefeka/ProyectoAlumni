<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivacitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privacities', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('phone');
            $table->boolean('localization');
            $table->timestamps();
        });

        $privacity = new App\Privacity();
        $privacity->phone = 0;
        $privacity->localization = 0;
        $privacity->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privacities');
    }
}
