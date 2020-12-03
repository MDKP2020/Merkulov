<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('patronymic')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->enum('academic_degree', array_keys(\App\Student::ACADEMIC_DEGREES))->default('bachelor');
            $table->foreignId('certificate_id')->unique()->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        //Добавлено из-за ошибки при работе с enum в postgres
        DB::transaction(function () {
            DB::statement('ALTER TABLE applicants DROP CONSTRAINT applicants_academic_degree_check;');
            DB::statement('ALTER TABLE applicants ADD CONSTRAINT applicants_academic_degree_check CHECK (academic_degree::TEXT = ANY (ARRAY[\'bachelor\'::CHARACTER VARYING, \'specialist\'::CHARACTER VARYING, \'master\'::CHARACTER VARYING, \'postgraduate\'::CHARACTER VARYING]::TEXT[]))');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
