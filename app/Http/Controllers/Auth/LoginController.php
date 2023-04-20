<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepo;
use App\Models\User;

use App\Utils\ErrorAndSuccessMessages;
use Illuminate\Http\Request;
use App\Utils\HttpStatusCode;
use Exception;
use \Response;
use \Validator;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    /**
     * userRepo
     *
     * @var mixed
     */
    protected  $userRepo;

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
     * redirectToGoogle Google Login
     *
     *
     *
     */
    public function redirectToGoogle()
    {

        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * handleGoogleCallback Google callback
     *
     *
     *
     */
    public function handleGoogleCallback()
    {

        $userSocial  = Socialite::driver('google')->stateless()->user();
        $user = User::where(['email' => $userSocial->getEmail()])->first();
        if ($user) {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
            $user = $this->userRepo->findBy('email', $userSocial->getEmail());
            return response()->json(['user' => $user, 'access_token' => $accessToken], HttpStatusCode::OK);
        } else {
            return response()->json(ErrorAndSuccessMessages::loginFailed, HttpStatusCode::Unauthorized);
        }
    }



    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|max:55',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response(['errors' => $validator->errors()->all()], HttpStatusCode::Unauthorized);
            }
            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];
            if (auth()->attempt($data)) {
                $accessToken = auth()->user()->createToken('authToken')->accessToken;
                $user = $this->userRepo->findBy('email', $request->email);
                return response()->json(['user' => $user, 'access_token' => $accessToken], HttpStatusCode::OK);
            } else {
                return response()->json(ErrorAndSuccessMessages::loginFailed, HttpStatusCode::Unauthorized);
            }
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::loginFailed, HttpStatusCode::BadRequest);
        }
    }
}
