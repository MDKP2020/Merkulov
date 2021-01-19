<?php


use App\AcademicYears;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AcademicYearsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AcademicYears::truncate();

        for ($i = 1999; $i < 2021; $i++){
            $st_date = Carbon::createFromDate($i, 9, 1, null);
            $end_date = Carbon::createFromDate($i+1, 5, 31, null);
            AcademicYears::create([
                'start_year'=>$st_date->toString(),
                'end_year'=>$end_date->toString()
            ]);
        }
    }
}
