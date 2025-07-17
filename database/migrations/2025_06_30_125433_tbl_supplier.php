<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tbl_supplier', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kd_supplier');
            $table->unsignedBigInteger('jenis_ikan');
            $table->string('alamat');
            $table->timestamps();

            $table->foreign('jenis_ikan')->references('id')->on('tbl_ikan')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('tbl_supplier');
    }
};
