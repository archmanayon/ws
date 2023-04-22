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
        Schema::create('update_bios', function (Blueprint $table) {
            $table->id();
            $table->string('biotext');
            $table->foreignId('biometrics_id')->nullable();
            $table->string('reason');
            $table->boolean('active')->default(true);
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_bios');
    }
};
