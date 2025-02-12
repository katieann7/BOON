<?php

namespace App\Http\Queries\V1\Materials;

use App\Http\Queries\Query;


class PlantQuery extends Query
{
    protected $with = [
        'stores',
        'orderingGroups',
        'loadingGroups',
        'materialGroups',
        'materialsByConversion',
    ];
}
