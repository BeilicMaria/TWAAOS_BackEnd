<?php

namespace  App\Http\Services;

use App\Http\Repositories\Repository;

use Illuminate\Container\Container as App;

class FacultyRepo extends Repository
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
        return '\App\Models\Faculty';
    }
}
