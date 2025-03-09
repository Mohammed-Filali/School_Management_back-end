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
        Schema::table('class_types', function (Blueprint $table) {
            // Add an image column to store the image URL or filename
            $table->string('image')->nullable();  // You can also use `text` if you plan to store image URLs that might be long.

            // Add a description column to store the description
            $table->text('description')->nullable();  // You can use `text` type if you expect longer descriptions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_types', function (Blueprint $table) {
            // Remove the image and description columns
            $table->dropColumn('image');
            $table->dropColumn('description');
        });
    }
};
