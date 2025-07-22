<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_konsumen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->varchar('kd_konsumen');
            $table->string('nama_konsumen');
            $table->string('no_tlp');
            $table->unsignedBigInteger('nama_pasar');
            $table->string('alamat');
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->timestamps();

            $table->foreign('nama_pasar','alamat','jam_buka','jam_tutup')->references('id')->on('tbl_pasar')->onDelete('cascade');

        });
    }

    public function down()
    {
         Schema::dropIfExists('tbl_konsumen');
    }
};
