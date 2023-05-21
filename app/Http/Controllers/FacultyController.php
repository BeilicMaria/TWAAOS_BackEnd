<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * facultyRepo
     *
     * @var mixed
     */
    protected $facultyRepo;

    /**
     * __construct
     *
     * @param  mixed $role
     * @return void
     */
    function __construct(FacultyRepo $faculty)
    {

    }

    /**
     * getIndex get all faculties
     *
     * @return void
     */
    public function index()
    {
        try {
            $faculties = $this->facultyRepo->all();
            if (!isset($faculties))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['faculties' => $faculties]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    /**
     * get faculty by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $faculty = Role::find($id);
            if (!isset($faculty))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['faculty' => $faculty]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
