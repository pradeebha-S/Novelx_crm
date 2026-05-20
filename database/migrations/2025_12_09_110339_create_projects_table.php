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

        Schema::create('projects', function (Blueprint $table) {

            $table->id();

            $table->string('project_name');

            $table->text('figma_link')->nullable();

            $table->string('document_link')->nullable();

            $table->string('client_mobile');

            $table->string('sheet_link')->nullable();

            $table->string('client_email');

            $table->string('client_name');

            $table->string('type');
 $table->string('tester_id')->nullable();
              $table->string('address');

            $table->timestamps();

        });

    }

    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::dropIfExists('projects');

    }

};

