<?php

namespace App\Repositories;

use App\Models\Address;
use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Log;

class AddressRepo extends Repository
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * specify model class name
     * @ return mixed
     */
    public function model()
    {
        return '\App\Models\Address';
    }

    /**
     * createOrUpdate
     *
     * @param  mixed $id
     * @param  mixed $country
     * @param  mixed $county
     * @param  mixed $city
     * @param  mixed $address
     * @return void
     */
    public function createOrUpdate($input)
    {
        try {
            $rules = array(
                'country' => 'required|string|max:55',
                'county' => 'required|string|max:55',
                'city' => 'required|string|max:55',
                'address' => 'required|string|max:55',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                Log::debug($validator->messages()->getMessages());
                return null;
            }
            $address = new Address();
            $address->country = $input['country'];
            $address->county = $input['county'];
            $address->city = $input['city'];
            $address->address = $input['address'];
            $address->save();
            return  $address->id;
        } catch (Exception $e) {
            Log::debug($e);
            return null;
        }
    }
}
