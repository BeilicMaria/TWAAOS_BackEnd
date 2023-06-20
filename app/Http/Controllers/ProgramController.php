<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * programRepo
     *
     * @var mixed
     */
    protected $programRepo;

    /**
     * __construct
     *
     * @param  mixed $program
     * @return void
     */
    function __construct(ProgramRepo $program)
    {

    }

    /**
     * getIndex get all programs
     *
     * @return void
     */
    public function index()
    {
        try {
            $programs = $this->programRepo->all();
            if (!isset($programs))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['programs' => $programs]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    /**
     * get program by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $program = Role::find($id);
            if (!isset($program))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['program' => $program]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
