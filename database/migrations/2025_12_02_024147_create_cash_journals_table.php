<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_journals', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->foreignId('coa_id')->constrained('coa')->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->boolean('is_inflow');
            $table->string('payment_method')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('transaction_date');
            $table->index('is_inflow');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_journals');
    }
};
