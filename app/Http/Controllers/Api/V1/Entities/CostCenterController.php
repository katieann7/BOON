<?php

namespace App\Http\Controllers\Api\V1\Entities;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Models\Entities\CostCenter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\V1\Entities\CostCenterResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CostCenterController extends Controller
{

    /**
     * Display a listing of the resource in datatable server-side.
     *
     * @return Yajra\DataTables\DataTables
     */
    public function index(Request $request)
    {
        $now = Carbon::today();

        $costCenters = CostCenter::query()
            ->whereDate('effective_from', '<=', $now)
            ->whereDate('effective_to', '>=', $now)
            ->get();

        return DataTables::of($costCenters)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  username  $username
     * @return \Illuminate\Http\Response
     */
    public function showByUsername($username)
    {
        try {
            $validator = Validator::make(
                ["username" => $this->parseParameter($username)],
                ["username" => ['required', 'string', 'min:4']],
            );
            $validator->validate();

            $now = Carbon::now();
            $costCenter = CostCenter::select('entities-cost_center_master.*')
                ->join('entities-user_master', 'entities-cost_center_master.id', '=', 'entities-user_master.cost_center_id')
                ->where('entities-user_master.effective_from', '<=', $now)
                ->where('entities-user_master.effective_to', '>=', $now)
                ->where('entities-user_master.username', $username)
                ->where('entities-user_master.effective_from', '<=', $now)
                ->where('entities-user_master.effective_to', '>=', $now)
                ->firstOrFail();

            return $this->successResponse("Cost Center fetched successfully", new CostCenterResource($costCenter));
        } catch (ValidationException $exception) {
            return $this->validationExceptionReponse($exception, ["username" => $username]);
        } catch (ModelNotFoundException $exception) {
            return $this->modelExceptionResponse($exception, ["username" => $username]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
