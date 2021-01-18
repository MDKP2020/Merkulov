<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('students', 'StudentController')->except([
    'create', 'edit']);

Route::apiResource('applicants', 'ApplicantController')->except([
    'create', 'edit']);

Route::apiResource('departments', 'DepartmentController')->except([
    'create', 'edit']);

Route::apiResource('transcripts', 'TranscriptsController')->except([
    'index', 'create', 'edit']);

Route::apiResource('majors', 'MajorController')->except([
    'create', 'edit']);

Route::apiResource('groups', 'GroupController')->except([
    'create', 'edit']);

Route::post( 'applicants/enrolleApplicant', 'ApplicantController@createStudent');
