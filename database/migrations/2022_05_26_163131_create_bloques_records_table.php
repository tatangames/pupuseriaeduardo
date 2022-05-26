<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloquesRecordsTable extends Migration
{
    /**
     * Listado de records
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloques_records', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('nombre', 100);
            $table->integer('cantidad');
            $table->string('imagen');
            $table->string('descripcion', 500)->nullable();
            $table->integer('posicion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bloques_records');
    }
}
