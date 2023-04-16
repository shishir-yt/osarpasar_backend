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
        Schema::create('quantities', function (Blueprint $table) {
            $table->id();
            $table->string('item_name')->nullable();
            $table->double('quantity')->default(1);
            $table->foreignId('user_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('service_provider_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('order_id')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('quantities');
    }
};