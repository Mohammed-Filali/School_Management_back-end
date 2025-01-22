<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('date_of_birth')->default('2025-01-01');
            $table->enum('gender',['m','f'])->default('m');
            $table->string('adress')->default('fvhgj');
            $table->string('phone',10)->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
            $table->dropColumn('gender');
            $table->dropColumn('adress');
            $table->dropColumn('phone');

        });
    }
};
