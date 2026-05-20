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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
                 $table->unsignedBigInteger('project_id');
    $table->string('document_name');

    $table->longText('content')->nullable();
    $table->string('file')->nullable();       
    $table->string('pdf_file')->nullable();  

    $table->enum('status', ['created', 'updated'])->default('created');

    $table->boolean('is_emailed')->default(0);
    $table->timestamp('emailed_at')->nullable();

    $table->timestamps();

    $table->foreign('project_id')
        ->references('id')
        ->on('projects')
        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
