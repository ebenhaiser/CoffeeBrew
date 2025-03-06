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
            $table->string('order_code')->unique();
            $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('set null');
            $table->decimal('total_price')->nullable();
            $table->tinyInteger('status')->default(0); // 0 = pending, 1 = completed; -1 = canceled
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('amount_change', 10, 2)->nullable();
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
