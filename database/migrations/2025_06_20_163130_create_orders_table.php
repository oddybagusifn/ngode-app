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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('full_name');
            $table->string('phone_number');
            $table->text('address');
            $table->string('postal_code')->nullable();
            $table->text('additional_info')->nullable();

            $table->enum('delivery_type', ['antar', 'toko']);
            $table->enum('payment_method', ['cod', 'kartu', 'qris']);

            $table->integer('product_price');
            $table->integer('packaging_fee')->default(20000);
            $table->integer('shipping_fee')->default(25000);
            $table->integer('service_fee')->default(5000);
            $table->integer('discount')->default(20000);
            $table->integer('total_price');

            $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'canceled'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
