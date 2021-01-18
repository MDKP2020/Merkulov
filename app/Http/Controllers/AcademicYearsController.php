<?php


namespace App\Http\Controllers;


use App\AcademicYears;
use App\Http\Requests\AcademicYearRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AcademicYearsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(AcademicYears::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AcademicYearRequest $request
     * @return void
     */
    public function store(AcademicYearRequest $request)
    {
        $academicYears = AcademicYears::create($request->all());

        if (!$academicYears) {
            return abort(422, 'Не удалось создать');
        }

        return response()->json($academicYears);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AcademicYears $academicYears
     * @return Response
     */
    public function show(AcademicYears $academicYears)
    {
        return response()->json($academicYears);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\AcademicYears $academicYears
     * @return Response
     */
    public function update(AcademicYearRequest $request, AcademicYears $academicYears)
    {
        $academicYears->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\AcademicYears $academicYears
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(AcademicYears $academicYears)
    {
        return response()->json($academicYears->delete());
    }
}
