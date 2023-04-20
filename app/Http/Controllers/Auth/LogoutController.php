<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Utils\ErrorAndSuccessMessages;
use Illuminate\Http\Request;
use App\Utils\HttpStatusCode;
use Lcobucci\JWT\Parser;
use \Response;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{

    function __construct()
    {
    }


    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $value = $request->bearerToken();
            $id = app(Parser::class)->parse($value)->claims()->get('jti');
            $accessToken = $user->token();
            DB::table('oauth_access_tokens')
                ->where('id', $id)
                ->delete();
            $accessToken->revoke();
            return response(ErrorAndSuccessMessages::logoutSuccess, HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::logoutSuccess, HttpStatusCode::BadRequest);
        }
    }
}
