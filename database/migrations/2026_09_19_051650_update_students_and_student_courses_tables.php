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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'class')) {
                $table->string('class')->nullable()->after('department');
            }
        });

        Schema::table('student_courses', function (Blueprint $table) {
            if (Schema::hasColumn('student_courses', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('student_courses', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'class')) {
                $table->dropColumn('class');
            }
        });

        Schema::table('student_courses', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }
};
