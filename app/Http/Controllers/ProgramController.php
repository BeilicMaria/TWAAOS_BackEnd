<?php

namespace App\Http\Controllers;

use App\Http\Services\ProgramRepo;
use App\Models\Program;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use \Response;

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
        $this->programRepo = $program;
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
            return Response::json(['programs' => $programs], HttpStatusCode::OK);
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
            $program = $this->programRepo->find($id);
            if (!isset($program))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json(['program' => $program], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
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
                'programs' => 'required',

            ]);
            if ($validator->fails() || count($request->programs) == 0) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            $programs = Program::insert($request->programs);
            return Response::make(['programs' => $programs], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }
}
