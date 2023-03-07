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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            
            $table->string('description')->nullable();

            $table->string('Monday_in')->nullable();
            $table->string('Monday_out')->nullable();
            $table->string('Monday_half')->nullable();

            $table->string('Tuesday_in')->nullable();
            $table->string('Tuesday_out')->nullable();
            $table->string('Tuesday_half')->nullable();

            $table->string('Wednesday_in')->nullable();
            $table->string('Wednesday_out')->nullable();
            $table->string('Wednesday_half')->nullable();

            $table->string('Thursday_in')->nullable();
            $table->string('Thursday_out')->nullable();
            $table->string('Thursday_half')->nullable();            
            
            $table->string('Friday_in')->nullable();
            $table->string('Friday_out')->nullable();
            $table->string('Friday_half')->nullable();

            $table->string('Saturday_in')->nullable();
            $table->string('Saturday_out')->nullable();
            $table->string('Saturday_half')->nullable();

            $table->string('Sunday_in')->nullable();
            $table->string('Sunday_out')->nullable();
            $table->string('Sunday_half')->nullable();

            $table->string('Manual_in')->nullable();
            $table->string('Manual_out')->nullable();
            $table->string('Manual_half')->nullable();
            
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
