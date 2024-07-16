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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('آیدی صاحب حساب');
            $table->tinyInteger('bank_id')->comment('آیدی بانک');
            $table->tinyInteger('type')->comment('نوع حساب (پس انداز- قرض الحسنه)');
            $table->tinyInteger('state')->comment('وضعیت حساب');
            $table->string('number')->unique()->comment('شماره حساب');
            $table->string('sheba')->unique()->comment('شماره شبا');
            $table->string('balance')->comment('موجودی');
            $table->date('end_date')->comment('تاریخ اعتبار');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
