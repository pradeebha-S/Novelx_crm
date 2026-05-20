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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            // Basic Details
            $table->string('category');
            $table->string('candidate_name');
            $table->string('technology');
            $table->string('work_status');
            $table->integer('experience')->nullable();

            // Employment Info
            $table->string('resume')->nullable();
            $table->integer('notice_period')->nullable();
            $table->decimal('current_salary', 10, 2)->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();

            // Contact Info
            $table->string('phone_number', 10);
            $table->string('alternate_phone_number', 10)->nullable();
            $table->string('email');

            // Location Info
            $table->string('state');
            $table->string('city');
            $table->enum('ready_to_reallocate', ['Yes', 'No'])->nullable();
            $table->enum('team_management', ['Yes', 'No'])->nullable();
            $table->enum('client_management', ['Yes', 'No'])->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
