<?php

namespace App\Http\Controllers;

use App\Repositories\AddressRepo;
use App\Utils\ErrorAndSuccessMessages;
use App\Utils\HttpStatusCode;
use Exception;
use \Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressesController extends Controller
{
    /**
     * addressRepo
     *
     * @var mixed
     */
    protected $addressRepo;

    /**
     * __construct
     *
     * @param  mixed $address
     * @return void
     */
    function __construct(AddressRepo $address)
    {
        $this->addressRepo = $address;
    }


    /**
     * get address by id
     *
     * @param  mixed $id
     * @return void
     */
    public function get($id)
    {
        try {
            $address = $this->addressRepo->findBy('id', $id);
            if (!isset($address))
                return Response::make(ErrorAndSuccessMessages::getDataFailed, HttpStatusCode::BadRequest);
            return Response::json(['address' => $address], HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }



    /**
     * post  (Adds or edits address)
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function post(Request $request, $id = null)
    {
        try {
            $request->validate([
                'country' => 'required|string|max:55',
                'county' => 'required|string|max:55',
                'city' => 'required|string|max:55',
                'address' => 'required|string|max:55',
            ]);
            $address = new Address();
            if (isset($id) && $id != 0) { // edit
                $address = Address::find($id);
            }
            $address->country = $request->input('country');
            $address->county = $request->input('county');
            $address->city = $request->input('city');
            $address->address = $request->input('address');
            $address->save();
            return Response::json(['address' => $address], HttpStatusCode::OK);
        } catch (
            Exception $e
        ) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }

    /**
     * delete
     *
     * @param  mixed $request
     * @return void
     */
    public function delete(Request $request)
    {
        try {
            $ids = $request->input('ids');
            if (!isset($ids)) {
                return Response::make(ErrorAndSuccessMessages::incompleteInput, HttpStatusCode::BadRequest);
            }
            $idsStr = implode(",", $ids);
            $addresses = Address::whereRaw("id in ($idsStr)")->get();
            if (count($addresses) > 0) {
                foreach ($addresses as $address) {
                    $address->delete();
                }
            }
            return Response::json(ErrorAndSuccessMessages::deleteSuccess, HttpStatusCode::OK);
        } catch (Exception $e) {
            Log::debug($e);
            return Response::json($e, HttpStatusCode::BadRequest);
        }
    }
}
