<?php

use App\Models\Teacher;
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
        Schema::table('class_type_courses', function (Blueprint $table) {
            $table->foreignIdFor(Teacher::class)->nullable()->constrained()->cascadeOnDelete();
            $table->integer('masseH')->default(100);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_type_courses', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']); // Drop the foreign key
            $table->dropColumn('teacher_id');
            $table->dropColumn('masseH') ;  // Drop the column
        });
    }
};
