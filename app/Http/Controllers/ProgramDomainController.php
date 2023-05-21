<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgramDomainController extends Controller
{
        /**
     * programdomainRepo
     *
     * @var mixed
     */
    protected $programdomainRepo;

    /**
     * __construct
     *
     * @param  mixed $programdomain
     * @return void
     */
    function __construct(ProgramDomainRepo $programdomain)
    {

    }

    /**
     * getIndex get all programsdomians
     *
     * @return void
     */
    public function index()
    {
        try {
            $programsdomains = $this->programdomainRepo->all();
            if (!isset($programsdomains))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['programs_domains' => $programsdomains]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    /**
     * get programdomain by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $programdomain = Role::find($id);
            if (!isset($program))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['program_domain' => $programdomain]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
