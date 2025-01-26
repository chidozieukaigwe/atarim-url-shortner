<?php

namespace App\Repositories;

use App\Models\Link;

class LinkRepository implements LinkRepositoryInterface
{
    public function find($id)
    {
        return Link::find($id);
    }

    public function create(array $data)
    {
        return Link::create($data);
    }
    //+
    public function where($column, $value)
    {
        return Link::where($column, $value)->first();
    }
}
