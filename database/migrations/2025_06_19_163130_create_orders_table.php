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
            $table->string('order_id')->unique();

            $table->string('person_name');
            $table->string('phone');
            $table->text('address');

            $table->string('province');    // ex: 33
            $table->string('city');        // ex: 33.07
            $table->string('subdistrict'); // ex: 33.07.09
            $table->string('village');     // ex: 33.07.09.1015
            $table->string('postal_code');

            $table->string('courier')->nullable();          // jne, tiki, etc
            $table->enum('delivery_method', ['antar', 'toko']);
            $table->enum('payment_method', ['cod', 'qris', 'kartu', 'midtrans'])->nullable(); // `midtrans` nanti diupdate dari callback

            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');

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
