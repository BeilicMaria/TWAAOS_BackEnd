<?php

namespace App\Http\Controllers;

use App\Http\Services\UserRepo;
use App\Models\Domain;
use App\Models\Program;
use App\Models\User;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Exception;
use DB;
use \Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    /**
     * userRepo
     *
     * @var mixed
     */
    protected $userRepo;

    /**
     * __construct
     *
     * @param  mixed $user
     * @return void
     */
    function __construct(UserRepo $user)
    {
        $this->userRepo = $user;
    }
    /**
     * index get all users
     *
     * @return void
     */
    public function index($page = null, $per_page = null, $sort = null, $order = null, $filter = null)
    {
        try {
            if (!isset($page) && !isset($per_pag)) {
                $page = 1;
                $per_page = 20;
            }
            $users = User::with(["studyPrograms"])->where('FK_roleId', '=', 2)->skip($per_page * ($page - 1))->take($per_page);
            $count = DB::table('users');
            if (isset($filter) && $filter != "" && $filter != "null") {
                $users->where('name', 'like', "%" . $filter . "%");
                $count->where('name', 'like', "%" . $filter . "%");
            }
            $count = $count->count();
            if (isset($sort) && isset($order))
                $users->orderBy($sort, $order);
            else {
                $users->orderBy("id", "desc");
                        $users = $users->get();
            $finalUsers = array();
            foreach ($users as  $user) {
                $domain = Domain::find($user->studyPrograms[0]['FK_domainId']);
                $user['domain'] = $domain['name'];
                $user['program'] = $user->studyPrograms[0]['acronym'];
                array_push($finalUsers, $user);
            }
            return Response::json(["total_count" => $count, "items" => $finalUsers], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }


    /**
     * get a user by id
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        try {
            $user = User::with(["role" => function ($query) {
                $query->select('id', 'role');
            }, "address" => function ($query) {
                $query->select('id', 'country', 'county', 'city', 'address');
            }])->find($id);
            if (!isset($user))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json(["user" => $user], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }

    /**
     * getStaffData
     *
     * @return void
     */
    public function getStaffData()
    {
        try {
            $dean = $this->userRepo->findBy('FK_roleId', 4);
            $newSecretaries = array();
            $secretaries = User::where('FK_roleId', '=', 3)->with(['studyPrograms'])->get();
            foreach ($secretaries as  $secretary) {
                $array = array();

                foreach ($secretary->studyPrograms as  $program) {
                    array_push($array, $program['id']);
                }
                $secretary['studyPrograms'] = $array;
                array_push($newSecretaries, $secretary);
            }
            return Response::json(["dean" => $dean, "secretaries" => $newSecretaries], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }

    /**
     * addOrUpdateStaff
     *
     * @param  mixed $request
     * @return void
     */
    public function addOrUpdateStaff(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:55',
                'lastName' => 'required|string|max:55',
                'email' => 'email|required|E-Mail',
                'secretaries' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            //decan
            if (isset($request->id)) {
                $dean = $this->userRepo->find($request->id);
                $dean['lastName'] = $request->lastName;
                $dean['firstName'] = $request->firstName;;
                $dean['email'] = $request->email;

                $dean->save();
            } else {
                $deanData = $request->all();
                $deanData['FK_roleId'] = 4;
                $dean = $this->userRepo->create($deanData);
            }
            //secretare
            $secretaries = $request->secretaries;
            foreach ($secretaries as  $secretary) {
                if (isset($secretary['id'])) {
                    $oldDecretary = $this->userRepo->getByIdWithRelationship($secretary['id'], 'studyPrograms');
                    $oldDecretary['lastName'] = $secretary['lastName'];
                    $oldDecretary['firstName'] = $secretary['firstName'];
                    $oldDecretary['email'] = $secretary['email'];
                    $oldDecretary->save();
                    if (isset($secretary['studyPrograms'])) {
                        $programs = $secretary['studyPrograms'];
                        $oldDecretary->studyPrograms()->sync($programs);
                    }
                } else {
                    $secretary['FK_roleId'] = 3;
                    $newSecretary = $this->userRepo->create($secretary);
                    if (isset($secretary['studyPrograms'])) {
                        $programs = $secretary['studyPrograms'];
                        foreach ($programs as  $program) {
                            $newSecretary->studyPrograms()->attach($program);
                        }
                    }
                }
            }
            $newSecretaries = array();
            $secretaries = User::where('FK_roleId', '=', 3)->with(['studyPrograms'])->get();
            foreach ($secretaries as  $secretary) {
                $array = array();

                foreach ($secretary->studyPrograms as  $program) {
                    array_push($array, $program['id']);
                }
                $secretary['studyPrograms'] = $array;
                array_push($newSecretaries, $secretary);
            }
            return Response::json(["dean" => $dean, "secretaries" => $newSecretaries], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }


    /**
     * importUsers
     *
     * @param  mixed $request
     * @return void
     */
    public function importUsers(Request $request)
    {
        try {
            $students = $request->students;
            foreach ($students as  $student) {
                $newStudent = $this->userRepo->create($student);
                $program = Program::where('acronym', '=', $student['program'])->get();
                $newStudent->studyPrograms()->attach($program[0]->id);
            }
            return Response::json("", HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::genericServerError, HttpStatusCode::BadRequest);
        }
    }



    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete(Request $request)
    {
        $idList = $request->all();
        try {
            if (!isset($idList)) {
                return Response::make(ErrorAndSuccessMessages::deleteFailed, HttpStatusCode::BadRequest);
            }
            foreach ($idList as  $idUser) {
                $user = $this->userRepo->find($idUser);
                $user->studyPrograms()->detach();
                $this->userRepo->delete($idUser);
            }
            return Response::make(ErrorAndSuccessMessages::deleteSuccess, HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::deleteFailed, HttpStatusCode::BadRequest);
        }
    }


    /**
     * addStudent
     *
     * @param  mixed $request
     * @return void
     */
    public function addOrUpdateStudent(Request $request)
    {
        try {
            $student = $request->all();
            $validator = Validator::make($student, [
                'lastName' => 'required|string|max:55',
                'firstName' => 'required|string|max:55',
                'email' => 'email|required|E-Mail',
                'financialStatus' => 'required|string|max:55',
                'peopleSoftId' => 'required|string|max:55',
                'fathersInitial' => 'required|string',
                'FK_roleId' => 'required',
                'program' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }
            if (isset($request->id)) {
                $student = $this->userRepo->find($request->id);
                $student['lastName'] = $request->lastName;
                $student['firstName'] = $request->lastName;
                $student['email'] = $request->lastName;
                $student['financialStatus'] = $request->lastName;
                $student['peopleSoftId'] = $request->firstName;;
                $student['fathersInitial'] = $request->email;
                $student->save();
                $student->studyPrograms()->sync($request->program);
            } else {
                $newStudent = $this->userRepo->create($student);
                $newStudent->studyPrograms()->attach($request->program);
            }
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::deleteFailed, HttpStatusCode::BadRequest);
        }
    }


    public function resetData()
    {
    }
}
