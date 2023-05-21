<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramUserController extends Controller
{
        /**
     * programuserRepo
     *
     * @var mixed
     */
    protected $programuserRepo;

    /**
     * __construct
     *
     * @param  mixed $programuser
     * @return void
     */
    function __construct(ProgramUserRepo $programuser)
    {

    }

    /**
     * getIndex get all programsusers
     *
     * @return void
     */
    public function index()
    {
        try {
            $programsusers = $this->programuserRepo->all();
            if (!isset($programsusers))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['programs_users' => $programsusers]], HttpStatusCode::OK);
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
            $programuser = Role::find($id);
            if (!isset($programuser))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['program_user' => $programuser]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
