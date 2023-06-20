<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DomainController extends Controller
{
    /**
     * domainRepo
     *
     * @var mixed
     */
    protected $domainRepo;

    /**
     * __construct
     *
     * @param  mixed $domain
     * @return void
     */
    function __construct(DomainRepo $domain)
    {

    }

    /**
     * getIndex get all domains
     *
     * @return void
     */
    public function index()
    {
        try {
            $domains = $this->domainRepo->all();
            if (!isset($domains))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['roles' => $domains]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    /**
     * get domain by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $domain = Role::find($id);
            if (!isset($domain))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['domain' => $domain]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
