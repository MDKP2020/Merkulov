<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->enum('academic_degree', array_keys(\App\Student::ACADEMIC_DEGREES))->default('bachelor');
            $table->foreignId('major_id')->constrained()->onDelete('cascade');
        });

        //Добавлено из-за ошибки при работе с enum в postgres
        DB::transaction(function () {
            DB::statement('ALTER TABLE groups DROP CONSTRAINT groups_academic_degree_check;');
            DB::statement('ALTER TABLE groups ADD CONSTRAINT groups_academic_degree_check CHECK (academic_degree::TEXT = ANY (ARRAY[\'bachelor\'::CHARACTER VARYING, \'specialist\'::CHARACTER VARYING, \'master\'::CHARACTER VARYING, \'postgraduate\'::CHARACTER VARYING]::TEXT[]))');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}