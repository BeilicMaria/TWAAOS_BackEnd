<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Repositories\RoleRepo;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Exception;
use Illuminate\Http\Request;
use \Response;
use Illuminate\Support\Facades\Log;

class RolesController extends Controller
{
    /**
     * roleRepo
     *
     * @var mixed
     */
    protected $roleRepo;

    /**
     * __construct
     *
     * @param  mixed $role
     * @return void
     */
    function __construct(RoleRepo $role)
    {

        000
    }

    /**
     * getIndex get all roles
     *
     * @return void
     */
    public function index()
    {
        try {
            $roles = $this->roleRepo->all();
            if (!isset($roles))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['roles' => $roles]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
        }
    }

    /**
     * get role by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $role = Role::find($id);
            if (!isset($role))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json([['role' => $role]], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
