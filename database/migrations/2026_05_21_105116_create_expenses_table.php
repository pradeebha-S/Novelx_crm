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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['common_exp', 'project_exp']);

            $table->string('month');

            $table->string('expense_type')->nullable();

            $table->unsignedBigInteger('project')->nullable();
            $table->decimal('amount', 10, 2);

            $table->string('proof')->nullable();

            $table->enum('status', ['paid', 'not_paid'])->default('not_paid');

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
