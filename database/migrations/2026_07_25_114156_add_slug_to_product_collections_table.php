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
        Schema::table('product_collections', function (Blueprint $table) {
            $table->string('slug'); 
            $table->enum('is_active',[1,0])->default(1); 
            $table->enum('show_on_home',[1,0])->default(0); 
            $table->enum('show_on_menu',[1,0])->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_collections', function (Blueprint $table) {
            $table->dropColumn('slug'); 
            $table->dropColumn('is_active');
            $table->dropColumn('show_on_menu'); 
            $table->dropColumn('show_on_home'); 
        });
    }
};
