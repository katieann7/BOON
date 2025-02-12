<?php

namespace App\Http\Queries;

use Illuminate\Http\Request;

class Query
{
    protected $with = [];

    public function queryWith(Request $request)
    {
        $withQuery = [];
        foreach ($this->with as $with) {
            if ($request->query($with) == "true") {
                array_push($withQuery, $with);
            }
        }
        return $withQuery;
    }
}
