<?php

namespace  App\Http\Repositories;

interface IRepository
{
    public function all($columns = array('*'));

    public function paginate($perPage = 25, $columns = array('*'));

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id, $columns = array('*'));

    public function findBy($field, $value, $columns = array('*'));

    public function findByAll($attribute, $value);

    public function getAllWithRelationship($relationships);

    public function getByIdWithRelationship($id, $relationships, $columns = array('*'));
}
