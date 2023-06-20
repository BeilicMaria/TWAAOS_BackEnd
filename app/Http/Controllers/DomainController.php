<?php

namespace App\Http\Controllers;

use App\Http\Services\DomainRepo;
use App\Models\Domain;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use \Response;
use Illuminate\Support\Facades\Validator;


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
        $this->domainRepo = $domain;
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
            return Response::json(['domains' => $domains], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
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
            $domain =  $this->domainRepo->find($id);
            if (!isset($domain))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['domain' => $domain]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }



    /**
     * post
     *
     * @param  mixed $request
     * @return void
     */
    public function post(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'domains' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            $domains = Domain::insert($request->domains);
            return Response::make(['domains' => $domains], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }
}
