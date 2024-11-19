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
        Schema::create('strip_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3);
            $table->string('status');
            $table->timestamp('payment_date');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

    }

    /*
     *
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strip_payments');
    }
};
