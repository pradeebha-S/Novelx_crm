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

        Schema::create('invoices', function (Blueprint $table) {

            $table->id();

            $table->string('invoice_no')->unique();

            $table->date('invoice_date');

            $table->unsignedBigInteger('project_id')->nullable();

            $table->unsignedBigInteger('bank_id')->nullable();

            $table->string('client_name')->nullable();

            $table->string('mobile')->nullable();

            $table->text('address')->nullable();

            $table->decimal('subtotal', 10, 2)->default(0);

            $table->decimal('tax', 10, 2)->default(0);

            $table->decimal('discount', 10, 2)->default(0);

               $table->string('tax_percentage')->default(0);

            $table->string('discount_percentage')->default(0);

            $table->decimal('paid_amount', 10, 2)->default(0);

            $table->decimal('total', 10, 2)->default(0);

            $table->text('remarks')->nullable();
   $table->text('status')->default('pending');

            $table->timestamps();

        });

    }

    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::dropIfExists('invoices');

    }

};

