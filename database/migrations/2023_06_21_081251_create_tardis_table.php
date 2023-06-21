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
        Schema::create('tardis', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id');
            $table->string('sy');
            $table->integer('month');
            $table->integer('total');
            $table->foreignId('tardi_id');
            $table->string('conforme')->nullable();
            $table->timestamp('con_date')->nullable();
            $table->string('head_sig')->nullable();
            $table->timestamp('sig_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tardis');
    }
};
