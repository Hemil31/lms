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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('title',150);
            $table->string('author',100);
            $table->string('isbn',13)->unique();
            $table->date('publication_date')->nullable();
            $table->enum('status', ['0', '1'])
                ->default('1')
                ->comment('0 => Not Available, 1 => Available');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
