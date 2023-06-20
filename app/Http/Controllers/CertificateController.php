<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * certificaterepor
     *
     * @var mixed
     */
    protected $certificaterepo;

    /**
     * __construct
     *
     * @param  mixed $certifcates
     * @return void
     */
    function __construct(CertificateRepo $certificaterepo)
    {

    }

    /**
     * getIndex get all certificates
     *
     * @return void
     */
    public function index()
    {
        try {
            $certifcates = $this->certificaterepo->all();
            if (!isset($certficates))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['certficates' => $certficates]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    /**
     * get role by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $certficate = Role::find($id);
            if (!isset($certficate))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['certficate' => $certficate]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
