<?php

namespace App\Http\Controllers;

use App\Http\Services\FacultyRepo;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Illuminate\Http\Request;
use \Response;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;

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
        $this->facultyRepo = $faculty;
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
            return Response::json(['faculties' => $faculties], HttpStatusCode::OK);
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
            $faculty = $this->facultyRepo->find($id);
            if (!isset($faculty))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['faculty' => $faculty]], HttpStatusCode::OK);
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
                'name' => 'required|string|max:55',
                'acronym' => 'required|string|max:55',
                'academic_year' => 'required|string',
            ]);
            if ($validator->fails()) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            $faculty = $this->facultyRepo->create($request->all());
            return Response::make(['faculty' => $faculty], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }
}
