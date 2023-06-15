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
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->boolean('active')->default(true);
          
            $table->string('timecard')->nullable();

            $table->string('student_id')->unique();

            $table->string('name');

            $table->string('username')->unique();

            $table->string('email')->unique();
            
            $table->string('password'); 
            $table->string('image_path')->nullable();
            $table->foreignId('sc')->nullable();
            $table->foreignId('shift_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();            
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('role_id')->nullable();
            $table->foreignId('head_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
