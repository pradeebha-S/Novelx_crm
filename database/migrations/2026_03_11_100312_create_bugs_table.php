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
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->nullable();
            $table->string('identified_by')->nullable();
            $table->string('panel')->nullable();
            $table->string('bug_type')->nullable();
            $table->string('bug_title')->nullable();
            $table->text('attachment')->nullable();
            $table->string('module')->nullable();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('debug_by')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Low');
            $table->text('testing_scenario')->nullable();
            $table->text('current_output')->nullable();
            $table->text('expected_output')->nullable();
            $table->string('status')->default('Pending')->nullable();
            $table->string('reopen_count')->nullable();
            $table->string('solved_by')->nullable();
            $table->text('suggestion')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
