<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_pasar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_pasar');
            $table->string('alamat');
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_pasar');
    }
};
