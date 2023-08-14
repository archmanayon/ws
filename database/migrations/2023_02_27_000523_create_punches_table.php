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
        Schema::create('punches', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id');
            $table->string('date', 6);
            $table->string('hour', 4);
            $table->string('in_out', 1);
            $table->string('biotext', 17)->unique();
            $table->foreignId('punchtype_id')->nullable();
            $table->boolean('active')->default(true);
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punches');
    }
};
