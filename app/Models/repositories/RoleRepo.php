<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;

class RoleRepo extends Repository
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
        return '\App\Models\Role';
    }
}
