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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('slug'); 
            $table->string('type'); 
            $table->text('description')->nullable();
            $table->string('image')->nullable(); 
            $table->enum('is_active',[1,0])->default(1);
            $table->enum('show_on_home',[1,0])->default(0); 
            $table->enum('show_on_menu',[1,0])->default(0);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
