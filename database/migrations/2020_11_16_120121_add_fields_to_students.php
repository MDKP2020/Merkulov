<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFieldsToStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('transcript')->nullable();
            $table->enum('status', array_keys(\App\Student::STATUSES))->comment(implode(",", array_values(\App\Student::STATUSES)))->default('studying');
            $table->enum('academic_degree', array_keys(\App\Student::ACADEMIC_DEGREES))->default('bachelor');
        });

        //Добавлено из-за ошибки при работе с enum в postgres
        DB::transaction(function () {
            DB::statement('ALTER TABLE students DROP CONSTRAINT students_status_check;');
            DB::statement('ALTER TABLE students ADD CONSTRAINT students_status_check CHECK (status::TEXT = ANY (ARRAY[\'studying\'::CHARACTER VARYING, \'expelled\'::CHARACTER VARYING, \'academic_leave\'::CHARACTER VARYING, \'graduated\'::CHARACTER VARYING]::TEXT[]))');
            DB::statement('ALTER TABLE students DROP CONSTRAINT students_academic_degree_check;');
            DB::statement('ALTER TABLE students ADD CONSTRAINT students_academic_degree_check CHECK (academic_degree::TEXT = ANY (ARRAY[\'bachelor\'::CHARACTER VARYING, \'specialist\'::CHARACTER VARYING, \'master\'::CHARACTER VARYING, \'postgraduate\'::CHARACTER VARYING]::TEXT[]))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('transcript');
            $table->dropColumn('status');
            $table->dropColumn('academic_degree');
        });
    }
}
