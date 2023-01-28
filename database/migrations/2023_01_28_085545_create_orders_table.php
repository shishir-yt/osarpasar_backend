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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('item_id')->nullable();
            $table->foreignId('other_item_id')->nullable();
            $table->foreignId('order_address_id')->nullable();
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->enum('status', ['Pending', 'Accepted', 'Rejected'])->default('Pending');
            $table->timestamps();
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
};