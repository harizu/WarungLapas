<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProduksTable extends Migration
{
    public function up()
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id', 'seller_fk_3281666')->references('id')->on('sellers');
        });
    }
}
