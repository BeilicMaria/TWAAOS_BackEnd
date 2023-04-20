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
            $value = $request->bearerToken();
            $id = (new Parser())->parse($value)->getClaim('jti');

            DB::table('oauth_access_tokens')
                ->where('id', $id)
                ->update([
                    'revoked' => true
                ]);

            return response(ErrorAndSuccessMessages::logoutSuccess, HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::make(ErrorAndSuccessMessages::logoutSuccess, HttpStatusCode::BadRequest);
        }
    }
}
