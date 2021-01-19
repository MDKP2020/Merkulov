<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $result = response()->json(Department::all());
        //print_r($result->getData());
        $countStudents = DB::select("
            select d.id as id, count(*) as count_students
            from students
            join student_group sg
            on sg.student_id  = students.id
            join \"groups\" g
            on g.id = sg.group_id
            join majors m
            on g.major_id = m.id
            join departments d
            on d.id  = m.department_id
            group by d.id"
            );

        $data = $result->getData();
        foreach ($data as $item)
            foreach ($countStudents as $count){
                if($count->id == $item->id)
                    $item->count = $count->count_students;
            }

        //$data['counts'] = $countStudents;



        $result->setData($data);

        return $result;
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
