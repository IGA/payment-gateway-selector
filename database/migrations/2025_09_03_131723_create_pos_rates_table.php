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
        Schema::create('pos_rates', function (Blueprint $table) {
            $table->id();
            $table->string('pos_name');
            $table->enum('card_type', ['credit', 'debit']);
            $table->enum('card_brand', ['bonus', 'world', 'axess', 'maximum', 'bankkart', 'paraf', 'cardfinans', 'saglam']);
            $table->unsignedInteger('installment')->default(1);
            $table->string('currency');
            $table->decimal('commission_rate', 5, 4);
            $table->decimal('min_fee', 8, 2);
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_rates');
    }
};
