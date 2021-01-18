<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantRequest;
use App\Applicant;
use App\Major;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use mysql_xdevapi\Exception;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(Applicant::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApplicantRequest $request
     * @return Response
     */
    public function store(ApplicantRequest $request)
    {
        $applicant = Applicant::create($request->except('majors'));

        if (!$applicant) {
            return abort(422, 'Не удалось создать');
        }



        $majors = str_split($request['majors']);
        foreach ($majors as $id) {
            if ($id >= 0) {
                $majors = Major::findOrFail($id);
                $applicant->majors()->attach($majors);
            }
        }

        return response()->json($applicant);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Applicant  $applicant
     * @return Response
     */
    public function show(Applicant $applicant)
    {
        return response()->json($applicant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Applicant  $applicant
     * @return Response
     */
    public function update(ApplicantRequest $request, Applicant $applicant)
    {
        //$applicant->update($request->all());
        $applicant->update($request->except('majors'));

        $applicantMajorIds = [];
        foreach ($applicant['majors'] as $item){
            $applicantMajorIds[] = $item['id'];
        }

        //print_r($applicantMajorIds);


        // $applicant->majors()->findOrFail($majors)

        $majors = explode(",", $request['majors']);
        foreach ($majors as $id) {
            if ($id >= 0 ) {
                $majors = Major::findOrFail($id);

                if(in_array($id, $applicantMajorIds))
                    $applicant->majors()->detach($majors);
                $applicant->majors()->attach($majors);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Applicant $applicant
     * @return Response
     * @throws \Exception
     */
    public function destroy(Applicant $applicant)
    {
        return response()->json($applicant->delete());
    }
}
