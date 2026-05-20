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
        Schema::create('loginentries', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->Datetime('check_in');
            $table->Datetime('check_out')->nullable();
            $table->string('image')->nullable();
            $table->string('remark')->nullable();
            $table->string('type')->nullable();
            $table->string('late_reason')->nullable();
            $table->string('ip_address', 45)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loginentries');
    }
};
