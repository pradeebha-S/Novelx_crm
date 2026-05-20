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



        Schema::create('user_details', function (Blueprint $table) {



            $table->id();



            $table->foreignId('user_id')->constrained()->onDelete('cascade');



            $table->string('aadhar_number');



            $table->string('pan_number')->nullable();



            $table->string('account_holder_name');



            $table->string('bank_name');

              $table->string('branch_name');



            $table->string('account_number');



            $table->string('ifsc_code');
              $table->string('upi');
 $table->string('is_active');


            $table->timestamps();



        });



    }







    /**



     * Reverse the migrations.



     */



    public function down(): void



    {



        Schema::dropIfExists('user_details');



    }



};



