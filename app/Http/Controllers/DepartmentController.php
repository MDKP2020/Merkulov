<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(Department::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepartmentRequest $request
     * @return Response
     */
    public function store(DepartmentRequest $request)
    {
        $department = Department::create($request->all());

        if (!$department) {
            return abort(422, 'Не удалось создать');
        }

        return response()->json($department);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return Response
     */
    public function show(Department $department)
    {
        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Department  $department
     * @return Response
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Department $department
     * @return Response
     * @throws \Exception
     */
    public function destroy(Department $department)
    {
        return response()->json($department->delete());
    }
}
