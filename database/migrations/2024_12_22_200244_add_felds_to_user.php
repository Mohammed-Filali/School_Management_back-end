<?php

use App\Models\StudentParent;
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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('blood_Type',['O-','O+','A-','A+','B-','B+','AB-','AB+',])->nullable();
            $table->foreignIdFor(StudentParent::class)->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('blood_Type');
            $table->dropForeignIdFor(StudentParent::class) ;

        });
    }
};
