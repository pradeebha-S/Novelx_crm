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
        Schema::create('student_task_histories', function (Blueprint $table) {
            $table->id();
            $table->string('task_id');

            $table->unsignedBigInteger('chapter_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('student_id');

            $table->string('status');
            $table->text('remark')->nullable();
            $table->string('spend_hour')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_task_histories');
    }
};
