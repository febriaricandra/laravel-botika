<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $division = $request->input('division');
        $items = $request->items ?? 10;

        $query = DB::table('employees')
            ->join('jobs', 'employees.job_id', '=', 'jobs.id')
            ->join('divisions', 'jobs.division_id', '=', 'divisions.id')
            ->select(
                'employees.id',
                'employees.name',
                'employees.status',
                'divisions.name',
                'jobs.title as job_name',
                'employees.created_at',
                'employees.updated_at'
            );


        if ($search) {
            $query->where('employees.name', 'like', "%{$search}%");
        }


        if ($status) {
            $query->where('employees.status', $status);
        }

        if ($division) {
            $query->where('divisions.id', $division);
        }

        $employees = $query->paginate($items);

        return response()->json($employees);
    }


    public function store(EmployeeRequest $request)
    {
        $employees = Employee::create($request->validated());
        return new EmployeeResource($employees);
    }

    public function update(EmployeeRequest $request, $id)
    {
        $employees = Employee::find($id);
        if (!$employees) {
            return ResponseHelper::error('Employee not found', 404);
        }
        $employees->update($request->validated());
        return new EmployeeResource($employees);
    }

    public function destroy($id)
    {
        $employees = Employee::find($id);
        if (!$employees) {
            return ResponseHelper::error('Employee not found', 404);
        }
        $employees->delete();
        return ResponseHelper::success('Employees Deleted successfully', 200);
    }
}
