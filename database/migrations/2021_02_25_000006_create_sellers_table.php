<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_seller');
            $table->string('alamat_seller')->nullable();
            $table->string('nomor_telp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
