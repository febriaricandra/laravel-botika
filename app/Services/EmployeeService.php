<?php

namespace App\Services;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllEmployees(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $division = $request->input('division');
        $items = $request->items ?? 5;

        $query = DB::table('employees')
            ->join('jobs', 'employees.job_id', '=', 'jobs.id')
            ->join('divisions', 'jobs.division_id', '=', 'divisions.id')
            ->select(
                'employees.id',
                'employees.name',
                'employees.status',
                'divisions.name as divisi_name',
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
            $query->where('divisions.name', 'like', "%{$division}%");
        }

        $employees = $query->paginate($items);

        return $employees;
    }
    
    public function createEmployee(array $data)
    {
        return Employee::create($data);
    }

    public function updateEmployee(Employee $employee, array $data)
    {
        $employee->update($data);
        return $employee;
    }

    public function deleteEmployee(Employee $employee)
    {
        $employee->delete();
    }
}
