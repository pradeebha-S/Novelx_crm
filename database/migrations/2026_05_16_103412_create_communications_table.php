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
        Schema::create('communications', function (Blueprint $table) {
            $table->id();

    $table->unsignedBigInteger('user_id');

    $table->string('communication_type');

    $table->string('priority_level');

    $table->string('reply_needed');

    $table->string('subject');

    $table->longText('content');
      $table->integer('is_replied')
                ->default(0);

            $table->integer('is_viewed')
                ->default(0);

    $table->string('status')->default('sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
