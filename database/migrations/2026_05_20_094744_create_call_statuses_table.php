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
        Schema::create('call_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');

            $table->string('call_status');

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_statuses');
    }
};