<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Http\Resources\DivisionResource;
use App\Http\Requests\DivisionRequest;
use App\Helpers\ResponseHelper;

class DivisionController extends Controller
{
    //
    public function index()
    {
        $divisions = Division::with('jobs')->get();
        return DivisionResource::collection($divisions);
    }

    public function store(DivisionRequest $request)
    {
        $division = Division::create($request->validated());
        return new DivisionResource($division);
    }

    public function show($id)
    {
        $division = Division::with('jobs')->find($id);
        if (!$division) {
            return ResponseHelper::error('Division not found', 404);
        }
        return new DivisionResource($division);
    }

    public function update(DivisionRequest $request, $id)
    {
        $division = Division::find($id);
        if (!$division) {
            return ResponseHelper::error('Division not found', 404);
        }
        $division->update($request->validated());
        return new DivisionResource($division);
    }

    public function destroy($id)
    {
        $division = Division::find($id);
        if (!$division) {
            return ResponseHelper::error('Division not found', 404);
        }
        $division->delete();
        return ResponseHelper::success('Division Deleted successfully', 200);
    }
}
