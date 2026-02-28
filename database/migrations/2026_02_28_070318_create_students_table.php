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
        Schema::create('students', function (Blueprint $table) {
            
            $table->id();

            $table->foreignId('institute_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            
            $table->string('full_name');
            $table->string('department')->nullable();
            $table->string('division')->nullable();
            $table->string('roll_number')->nullable();

            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();

            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
      

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
