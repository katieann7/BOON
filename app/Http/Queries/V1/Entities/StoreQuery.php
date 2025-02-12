<?php

namespace App\Http\Queries\V1\Entities;

use App\Http\Queries\Query;


class StoreQuery extends Query
{
    protected $with = [
        'CostCenter',
        'plants'
    ];
}
