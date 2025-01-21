<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Http\Resources\JobResource;
use App\Http\Requests\JobRequest;
use App\Helpers\ResponseHelper;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('division')->get();
        return JobResource::collection($jobs);
    }

    public function show($id)
    {
        $job = Job::with('division')->find($id);
        if (!$job) {
            return ResponseHelper::error('Division not found', 404);
        }
        return new JobResource($job);
    }

    public function store(JobRequest $request)
    {
        $job = Job::create($request->validated());
        return new JobResource($job);
    }

    public function update(JobRequest $request, $id)
    {
        $job = Job::find($id);
        if (!$job) {
            return ResponseHelper::error('Division not found', 404);
        }
        $job->update($request->validated());
        return new JobResource($job);
    }

    public function destroy($id)
    {
        $job = Job::find($id);
        if (!$job) {
            return ResponseHelper::error('Job not found', 404);
        }
        $job->delete();
        return ResponseHelper::success('Job Deleted successfully', 200);
    }
}
