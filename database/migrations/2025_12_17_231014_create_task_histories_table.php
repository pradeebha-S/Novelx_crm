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
        Schema::create('task_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('staff_id')->nullable();

            $table->string('status');
            $table->string('reopen_type')->nullable();
            $table->string('reassign_to')->nullable();
            $table->text('remark')->nullable();
            $table->string('spending_hour')->nullable();

            $table->timestamps();

            // Manual references
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('staff_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_histories');
    }
};
