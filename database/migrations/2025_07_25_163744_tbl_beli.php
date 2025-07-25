<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_beli', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd_beli');
            $table->unsignedBigInteger('kd_supplier');
            $table->date('tgl_beli');
            $table->unsignedBigInteger('jenis_ikan');
            $table->double('harga_beli');
            $table->double('jml_ikan');
            $table->double('total_harga');
            $table->string('bukti_pembayaran');

            $table->timestamps();

            $table->foreign('kd_supplier')->references('id')->on('tbl_supplier')->onDelete('cascade');
            $table->foreign('jenis_ikan')->references('id')->on('tbl_ikan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_beli');
    }
};
