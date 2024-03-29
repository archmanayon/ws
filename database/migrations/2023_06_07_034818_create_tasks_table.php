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
        Schema::create('tasks', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id');
            $table->string('task_done');
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();         
            $table->string('head_id')->nullable();
            $table->string('biotext')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
