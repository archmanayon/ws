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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            
            $table->string('description')->nullable();

            $table->string('Monday_am_in')->nullable();
            $table->string('Monday_am_out')->nullable();
            $table->string('Monday_pm_in')->nullable();
            $table->string('Monday_pm_out')->nullable();

            $table->string('Tuesday_am_in')->nullable();
            $table->string('Tuesday_am_out')->nullable();
            $table->string('Tuesday_pm_in')->nullable();
            $table->string('Tuesday_pm_out')->nullable();

            $table->string('Wednesday_am_in')->nullable();
            $table->string('Wednesday_am_out')->nullable();
            $table->string('Wednesday_pm_in')->nullable();
            $table->string('Wednesday_pm_out')->nullable();

            $table->string('Thursday_am_in')->nullable();
            $table->string('Thursday_am_out')->nullable();
            $table->string('Thursday_pm_in')->nullable();   
            $table->string('Thursday_pm_out')->nullable();         
            
            $table->string('Friday_am_in')->nullable();
            $table->string('Friday_am_out')->nullable();
            $table->string('Friday_pm_in')->nullable();
            $table->string('Friday_pm_out')->nullable();

            $table->string('Saturday_am_in')->nullable();
            $table->string('Saturday_am_out')->nullable();
            $table->string('Saturday_pm_in')->nullable();
            $table->string('Saturday_pm_out')->nullable();

            $table->string('Sunday_am_in')->nullable();
            $table->string('Sunday_am_out')->nullable();
            $table->string('Sunday_pm_in')->nullable();
            $table->string('Sunday_pm_out')->nullable();

            $table->string('Manual_am_in')->nullable();
            $table->string('Manual_am_out')->nullable();
            $table->string('Manual_pm_in')->nullable();
            $table->string('Manual_pm_out')->nullable();
            
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
        Schema::dropIfExists('shifts');
    }
};
