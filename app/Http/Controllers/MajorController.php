<?php
namespace App\Http\Controllers;

use App\Major;
use App\Http\Requests\MajorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(Major::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MajorRequest $request
     * @return Response
     */
    public function store(MajorRequest $request)
    {
        $major = Major::create($request->all());

        if (!$major) {
            return abort(422, 'Не удалось создать');
        }

        return response()->json($major);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Major $major
     * @return Response
     */
    public function show(Major $major)
    {
        return response()->json($major);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Major $major
     * @return Response
     */
    public function update(MajorRequest $request, Major $major)
    {
        $major->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Major $major
     * @return Response
     * @throws \Exception
     */
    public function destroy(Major $major)
    {
        return response()->json($major->delete());
    }
}
