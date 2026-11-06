<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trans_order_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_order');
            $table->foreign('id_order')->references('id')->on('trans_order')->onDelete('cascade');
            $table->unsignedInteger('id_service');
            $table->foreign('id_service')->references('id')->on('type_of_service')->onDelete('cascade');
            $table->integer('qty');
            $table->double('subtotal', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_order_detail');
    }
};
