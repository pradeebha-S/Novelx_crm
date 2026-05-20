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

        Schema::create('project_documents', function (Blueprint $table) {

            $table->id();
$table->string('project_id');
             $table->string('platform');

        $table->string('user_id');

        $table->string('password');

         $table->string('password_hint');

        $table->string('document')->nullable(); 

            $table->timestamps();

        });

    }



    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::dropIfExists('project_documents');

    }

};

