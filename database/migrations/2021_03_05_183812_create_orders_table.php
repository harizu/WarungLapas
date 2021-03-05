<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no', 50)->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('warga_binaan_id');
            $table->decimal('total', 15, 2);
            $table->decimal('biaya_layanan', 15, 2);
            $table->tinyInteger('status')->default(0)->comment('-2: canceled by buyer, -1: canceled by admin, 0: new order, 1: on process, 2: complete');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
