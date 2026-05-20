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

         Schema::create('wfhs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')

                ->constrained('users')

                ->cascadeOnDelete();

            $table->date('from');

            $table->date('to');

            $table->text('reason');

            $table->string('remark')->default('null');

            $table->string('reply')->default('null');

            $table->boolean('is_replied')->default(0);

              $table->string('informed_to')->nullable();

               $table->enum('mailed', ['yes', 'no'])->nullable();

            $table->timestamps();

        });

    }

    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::dropIfExists('wfhs');

    }

};

