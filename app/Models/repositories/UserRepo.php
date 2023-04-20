<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;

class UserRepo extends Repository
{

    /**
     * __construct
     *
     * @param  mixed $app
     * @return void
     */
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
        return '\App\Models\User';
    }
}
