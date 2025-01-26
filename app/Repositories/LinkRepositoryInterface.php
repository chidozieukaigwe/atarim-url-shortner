<?php

namespace App\Repositories;

interface LinkRepositoryInterface
{
    public function find($id);
    public function create(array $data);
    public function where(string $col, $operator, $data);
}
