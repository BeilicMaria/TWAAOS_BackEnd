<?php

namespace App\Http\Controllers;

use App\Http\Services\CertificateRepo;
use App\Http\Services\UserRepo;
use App\Models\Certificate;
use App\Models\Domain;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use \Response;
use Illuminate\Support\Facades\Config;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\GenericUtils;
use App\Utils\HttpStatusCode;

class CertificateController extends Controller
{
    /**
     * certificaterepor
     *
     * @var mixed
     */
    protected $certificateRepo, $userRepo;

    /**
     * __construct
     *
     * @param  mixed $certifcates
     * @return void
     */
    function __construct(CertificateRepo $certificateRepo, UserRepo $userRepo)
    {
        $this->certificateRepo = $certificateRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * getIndex get all certificates
     *
     * @return void
     */
    public function index($page = null, $per_page = null,  $order = null, $filter = null)
    {
        try {
            if (!isset($page) && !isset($per_pag)) {
                $page = 1;
                $per_page = 20;
            }
            $facultyData = Faculty::all();
            $dean = $this->userRepo->findBy('FK_roleId', 4);
            $secretaries = User::where('FK_roleId', '=', 3)->with(['studyPrograms'])->orderBy("id", "asc")->get();
            $certificates = Certificate::with(["user_student", "user_student.studyPrograms"])->skip($per_page * ($page - 1))->take($per_page);
            if (isset($filter) && $filter != "" && $filter != "null") {
                $certificates->where('number', 'like', "%" . $filter . "%")->orWhere('firstName', 'like', "%" . $filter . "%");
            }
            $count = $certificates->count();
            $certificates = $certificates->whereIn("status", array(1, 2));
            $certificates->orderBy("id", "desc");
            $certificates = $certificates->get();
            $finalCertificates = array();
            foreach ($certificates as  $cerificate) {
                $domain = Domain::find($cerificate->user_student->studyPrograms[0]['FK_domainId']);
                $cerificate['lastName'] = $cerificate->user_student['lastName'];
                $cerificate['email'] = $cerificate->user_student['email'];
                $cerificate['year'] = $cerificate->user_student['year'];
                $cerificate['financialStatus'] = $cerificate->user_student['financialStatus'];
                $cerificate['academic_year'] = $facultyData[0]['academic_year'];
                $cerificate['program'] =  $cerificate->user_student->studyPrograms[0]['name'];
                $cerificate['domain'] = $domain['name'];
                $cerificate['firstName'] = $cerificate->user_student['firstName'];
                $cerificate['fathersInitial'] = $cerificate->user_student['fathersInitial'];
                $cerificate['status'] = $cerificate['status'] == 1 ? "În procesare" : ($cerificate['status'] == 2 ? "Aprobat" : "Respins");
                array_push($finalCertificates, $cerificate);
            }
            return Response::json(["total_count" => $count, "items" => $finalCertificates, "dean" => $dean, 'secretaries' => $secretaries], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }

    /**
     * get
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            if (!isset($id)) {
                return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
            }
            $cerificates = Certificate::where('FK_studentId', '=', $id)->with(['user_student'])->get();
            $array = array();
            foreach ($cerificates as  $cerificate) {
                $cerificate['status'] = $cerificate['status'] == 1 ? "În procesare" : ($cerificate['status'] == 2 ? "Aprobat" : "Respins");
                array_push($array, $cerificate);
            }
            return Response::json(["cerificates" => $array], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }


    /**
     * get role by id
     *
     * @param  mixed $id
     * @return void
     */
    public function post(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'required|string',
                'FK_studentId' => 'required',

            ]);
            if ($validator->fails()) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            $data = $request->all();
            $data['date'] = date("Y/m/d");
            $data['status'] = 1;
            $this->certificateRepo->create($data);

            return Response::json("", HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }


    /**
     * aproveCerificate
     *
     * @param  mixed $request
     * @return void
     */
    public function aproveCerificate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'number' => 'required',
                'email' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            $cerificate = Certificate::find($request->id);
            $cerificate['status'] = 2;
            $cerificate['number'] =  $request->number;
            $cerificate['FK_secretaryId'] = $request->FK_secretaryId;
            $cerificate->save();
            $from = "testplatforma321@gmail.com";
            $to = $request->email;
            $cc = [];
            $subject = ErrorAndSuccessMessages::registerConfirmationSubject;
            $message = "<h2>Salutări!"  . "</h2> <p> " . 'Solicitatea ta pentru adeverința de student a fost aprobată. Te aștepăm să o ridic în 1-2 zile.' . "</p>";
            $layout = 'emails.template';
            GenericUtils::sendMail($from, $to, $cc, null, $subject, $message, null, $layout);
            return Response::json("", HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }



    /**
     * rejectCertificate
     *
     * @param  mixed $request
     * @return void
     */
    public function rejectCertificate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                // 'FK_secretaryId' => 'required',
                'email' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            $cerificate = Certificate::find($request->id);
            $cerificate['status'] = 3;
            $cerificate['FK_secretaryId'] = $request->FK_secretaryId;
            $cerificate->save();
            $from = "testplatforma321@gmail.com";
            $to = $request->email;
            $cc = [];
            $subject = ErrorAndSuccessMessages::registerConfirmationSubject;
            $message = "<h2>Salutări!"  . "</h2> <p> " . 'Ne pare rău să te anunțăm că adeverința solicitată nu poate fi eliberată. Pentru mai multe detalii contactează secretariatul.' . "</p>";
            $layout = 'emails.template';
            GenericUtils::sendMail($from, $to, $cc, null, $subject, $message, null, $layout);
            return Response::json("", HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }
}
