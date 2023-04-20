<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\AddressRepo;
use App\Repositories\RoleRepo;
use App\Repositories\UserRepo;
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
     * addressRepo, roleRepo, userRepo, companyRepo
     *
     * @var mixed
     */
    protected $addressRepo, $roleRepo, $userRepo;


    /**
     * __construct
     *
     * @param  mixed $address
     * @param  mixed $role
     * @param  mixed $user
     * @return void
     */
    function __construct(AddressRepo $address, RoleRepo $role, UserRepo $user)
    {
        $this->addressRepo = $address;
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

            //create Address
            $userValidatedData['FK_addressId'] = $this->addressRepo->create($request->all());
            if (!isset($userValidatedData['FK_addressId'])) {
                return Response::make(ErrorAndSuccessMessages::validationError, HttpStatusCode::BadRequest);
            }

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
