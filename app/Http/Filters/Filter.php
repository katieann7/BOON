<?php

namespace App\Http\Filters;

use Illuminate\Http\Request;

class Filter
{
    protected $allowedParams = [];

    protected $columnMap = [];

    protected $operatorMap = [];

    public function transform(Request $request)
    {
        $eloquentQuery = [null];

        foreach ($this->allowedParams as $params => $operators) {
            $query = $request->query($params);

            if (!isset($query)) {
                continue;
            }

            $column = $this->columnMap[$params] ?? $params;

            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloquentQuery = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloquentQuery;
    }
}
