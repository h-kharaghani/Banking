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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id')->comment('آیدی حساب');
            $table->string('number')->unique()->comment('شماره کارت');
            $table->tinyInteger('state')->comment('وضعیت');
            $table->tinyInteger('type')->comment('نوع کارت');
            $table->date('issue_date')->comment('تاریخ صدور');
            $table->date('end_date')->comment('تاریخ اعتبار');
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
