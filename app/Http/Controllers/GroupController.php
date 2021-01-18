<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\GroupRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json(Group::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupRequest $request
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $group = Group::create($request->all());

        if (!$group) {
            return abort(422, 'Не удалось создать');
        }

        return response()->json($group);

    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return Response
     */
    public function show($id)
    {
        return response()->json(Group::with('students')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Group $group
     * @return Response
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Major $major
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Group $group)
    {
        return response()->json($group->delete());
    }
}