<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * reportRepo
     *
     * @var mixed
     */
    protected $reportRepo;

    /**
     * __construct
     *
     * @param  mixed $report
     * @return void
     */
    function __construct(ReportRepo $report)
    {

    }

    /**
     * getIndex get all reports
     *
     * @return void
     */
    public function index()
    {
        try {
            $reports = $this->reportRepo->all();
            if (!isset($reports))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['reports' => $reports]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    /**
     * get report by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $report = Role::find($id);
            if (!isset($report))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['report' => $report]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
