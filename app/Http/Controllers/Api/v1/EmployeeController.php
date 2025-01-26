<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Http\Resources\EmployeeResource;
// use App\Http\Resources\EmployeesResource;
use Illuminate\Http\Request;
use App\Services\EmployeeService;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = (new EmployeeService)->getAllEmployees($request);
        return response()->json($employees);
    }


    public function store(EmployeeRequest $request)
    {
        $employees = (new EmployeeService)->createEmployee($request->validated());
        return new EmployeeResource($employees);
    }

    public function update(EmployeeRequest $request, $id)
    {
        $employees = Employee::find($id);
        if (!$employees) {
            return ResponseHelper::error('Employee not found', 404);
        }
        $employees = (new EmployeeService)->updateEmployee($employees, $request->validated());
        return new EmployeeResource($employees);
    }

    public function destroy($id)
    {
        $employees = Employee::find($id);
        if (!$employees) {
            return ResponseHelper::error('Employee not found', 404);
        }
        $employees = (new EmployeeService)->deleteEmployee($employees);
        return ResponseHelper::success('Employees Deleted successfully', 200);
    }
}
