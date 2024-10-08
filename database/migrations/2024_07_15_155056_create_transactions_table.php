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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('card_id')->comment('آیدی کارت مبدا');
            $table->string('amount')->comment('مبلغ تراکنش');
            $table->string('description')->comment('توضیحات');
            $table->tinyInteger('type')->comment('نوع تراکنش');
            $table->timestamps();
            $table->foreign('card_id')->references('id')->on('cards')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
