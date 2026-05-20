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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->string('name');
            $table->string('mobile');
            $table->string('email')->unique();
            $table->string('personal_email')->unique();
            $table->string('password');
              $table->string('password_hint')->nullable();
            $table->text('profile_image')->nullable();
            $table->string('role')->default('staff');
            $table->string('designation')->nullable();
            $table->string('intern_period')->nullable();
            $table->string('address')->nullable();
            $table->string('dob')->nullable();
              $table->string('doj')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('otp')->nullable();
            $table->date('blocked_at')->nullable();
            $table->boolean('is_break')->default(0);
            $table->boolean('otp_verified')->default(0);
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
             $table->string('type')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
