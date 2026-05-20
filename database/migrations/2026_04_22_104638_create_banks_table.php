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

        Schema::create('banks', function (Blueprint $table) {

            $table->id();

            $table->string('bank_name');

            $table->string('account_number')->unique();

            $table->string('holder_name');

            $table->string('ifsc_code');

            $table->string('branch_name');

            $table->boolean('is_active')->default(1);

            $table->boolean('gst')->default(0);
            $table->string('status')->default('pending');
  $table->string('upi')->nullable();


            $table->timestamps();

        });

    }



    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::dropIfExists('banks');

    }

};

