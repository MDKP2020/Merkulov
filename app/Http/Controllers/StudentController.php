<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\StudentRequest;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(Student::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudentRequest $request
     * @return Response
     */
    public function store(StudentRequest $request)
    {
        $student = Student::create($request->except('group_id'));

        if($request['group_id']) {
            $group = Group::findOrFail($request['group_id']);
            $student->groups()->attach($group);
        }

        if (!$student) {
            return abort(422, 'Не удалось создать');
        }

        return response()->json($student);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return Response
     */
    public function show(Student $student)
    {
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *e vt
     * @param Request $request
     * @param  \App\Student  $student
     * @return Response
     */
    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->except('group_id'));

        if($request['group_id']) {
            $group = Group::findOrFail($request['group_id']);
            $student->groups()->attach($group);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Student $student
     * @return Response
     * @throws \Exception
     */
    public function destroy(Student $student)
    {
        return response()->json($student->delete());
    }
}
