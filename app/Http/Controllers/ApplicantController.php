<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantRequest;
use App\Applicant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        $applicant = Applicant::create($request->all());

        if (!$applicant) {
            return abort(422, 'Не удалось создать');
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
        $applicant->update($request->all());
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

    /**
     * Create student from applicant
     *
     * @param  Request $request
     * @return Response
     */
    public function createStudent(Request $request)
    {
        $status = false;

        $id = DB::table('students')->insertGetId(
            [
                'name' => $request['name'],
                'surname' => $request['surname'],
                'patronymic' => $request['patronymic']
            ]
        );

        if($id){
            $cur_appl = DB::table('applicants')
                ->where('id', $request['id'])
                ->update(
                    [
                        'student_id' => $id,
                        'is_enrolled' => true
                    ]
                );
            $status = true;
        }
        return response()->json($status);
    }
}
