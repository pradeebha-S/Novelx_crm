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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('project_id');
            $table->string('module_id');
            $table->string('module_type');
            $table->string('task_name');
            $table->string('estimated_time')->nullable();
            $table->string('spending_hour')->nullable();
            $table->date('due_date');
            $table->string('assign_to')->nullable();
            $table->string('task_status')->default('new');
            $table->string('testing_complete')->default('new');
            $table->text('remark')->nullable();
            $table->string('task_type');
              $table->string('tester_id');
              $table->string('tested_by');
            $table->string('test_status')->default('incomplete');
            $table->string('priority');
            $table->string('start_date');
            $table->text('task_description');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
