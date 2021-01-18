<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantMajorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_major', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('major_id');
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('major_id')->references('id')->on('majors');
            $table->foreign('applicant_id')->references('id')->on('applicants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant_major');
    }
}
