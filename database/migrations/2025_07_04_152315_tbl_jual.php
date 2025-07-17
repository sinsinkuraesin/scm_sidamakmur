<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_jual', function (Blueprint $table) {
            $table->bigIncrements('jual_id');
            $table->unsignedBigInteger('nama_konsumen');
            $table->date('tgl_jual');
            $table->string('nama_pasar');
            $table->timestamps();

            $table->foreign('nama_konsumen')->references('id')->on('tbl_konsumen')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_jual');
    }
};
