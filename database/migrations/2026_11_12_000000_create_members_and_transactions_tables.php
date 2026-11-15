<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('member_number')->unique();
            $table->string('name');
            $table->text('address');
            $table->string('phone', 30);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['simpanan', 'pinjaman']);
            $table->string('type', 30);
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['category', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('members');
    }
};
