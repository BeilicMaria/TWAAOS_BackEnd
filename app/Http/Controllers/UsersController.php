<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Exception;
use DB;
use \Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{

    function __construct()
    {
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
            $users = User::with(["role" => function ($query) {
                $query->select('id', 'role');
            }, "address" => function ($query) {
                $query->select('id', 'country', 'county', 'city', 'address');
            }])->skip($per_page * ($page - 1))->take($per_page);
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
            }

            $users = $users->get();
            return Response::json([["total_count" => $count, "items" => $users]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return Response::json($e->getMessage(), HttpStatusCode::BadRequest);
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
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
