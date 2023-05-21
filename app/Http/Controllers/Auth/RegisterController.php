<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\UserRepo;
use App\Http\Services\RoleRepo;

use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Exception;
use \Response;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class RegisterController extends Controller
{


    /**
     *  roleRepo, userRepo, companyRepo
     *
     * @var mixed
     */
    protected  $roleRepo, $userRepo;


    /**
     * __construct
     *
     * @param  mixed $address
     * @param  mixed $role
     * @param  mixed $user
     * @return void
     */
    function __construct(RoleRepo $role, UserRepo $user)
    {

        $this->roleRepo = $role;
        $this->userRepo = $user;
    }


    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            //validation
            $userValidatedData =  $request->validate([
                'userName' => 'required|string|max:55|unique:users',
                'email' => 'email|required|E-Mail|unique:users',
                'password' => 'required|confirmed',
                'fullName' => 'required|string|max:55',
                'phone' => 'required|numeric|digits:10',

            ]);

            $userValidatedData['password'] = bcrypt($request->password);


            //create user

            $role = $this->roleRepo->findBy('role', 'user');
            $userValidatedData['FK_roleId'] = $role['id'];
            $user = $this->userRepo->create($userValidatedData);
            $accessToken = $user->createToken('authToken')->accessToken;
            DB::commit();
            return Response::json([['user' => $user, 'access_token' => $accessToken]], HttpStatusCode::OK);
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::failedRegistration, HttpStatusCode::BadRequest);
        }
    }
}
