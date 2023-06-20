<?php

namespace  App\Http\Services;

use Illuminate\Container\Container as App;

class DomainRepo extends Repository
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
        return '\App\Models\Domain';
    }
}