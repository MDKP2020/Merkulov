<?php

use App\Applicant;
use Illuminate\Database\Seeder;

class ApplicantSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Applicant::truncate();
        factory(Applicant::class, 15)->create();
    }
}
