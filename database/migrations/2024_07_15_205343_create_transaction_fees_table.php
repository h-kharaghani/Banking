<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id')->comment('آیدی تراکنش');
            $table->integer('amount')->comment('مقدار کارمزد');
            $table->timestamps();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_fees');
    }
};
