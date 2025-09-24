<?php

namespace App\Services\Report\WeeklyReport;

use App\{
    Repositories\Report\WeeklyReport\WeeklyReportRepositoryInterface,
    Traits\ApiResponse
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Throwable;

class WeeklyReportService
{
    use ApiResponse;

    public function __construct(
        protected WeeklyReportRepositoryInterface $weekRepo
    ) {}

    public function index()
    {
        return $this->successResponse([
            'weeklyReports' => $this->weekRepo->getAll()
        ]);
    }

    public function show($id)
    {
        return $this->successResponse([
            'weeklyReport' => $this->weekRepo->find($id)
        ]);
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'user_id'             => 'required|exists:users,id',
            'vehicle_id'          => 'required|exists:vehicles,vehicleId',
            'report_date'         => 'required|date',
            'note'                => 'nullable|string|max:255',
            'details'             => 'required|array|min:1',
            'details.*.component' => 'required|string|max:100',
            'details.*.position'  => 'required|string|max:100',
            'details.*.file'      => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480', 
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $weeklyReport = $this->weekRepo->store($req);
            return $this->successResponse(['weeklyReport' => $weeklyReport], 'Weekly report created');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to create weeklyReport: '.$e->getMessage(), 500);
        }
    }

    public function update(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'user_id'             => 'sometimes|exists:users,id',
            'vehicle_id'          => 'sometimes|exists:vehicles,vehicleId',
            'report_date'         => 'sometimes|date',
            'note'                => 'nullable|string|max:255',
            'status'              => 'sometimes|in:0,1,2,3',
            'details'             => 'sometimes|array|min:1',
            'details.*.component' => 'required_with:details|string|max:100',
            'details.*.position'  => 'required_with:details|string|max:100',
            'details.*.file'      => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:20480',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors(), 422);
        }

        try {
            $weeklyReport = $this->weekRepo->update($req, $id);
            return $this->successResponse(['weeklyReport' => $weeklyReport], 'Weekly report updated');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to update weeklyReport: '.$e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        try {
            $this->weekRepo->delete($id);
            return $this->successResponse([], 'Weekly report deleted');
        } catch (Throwable $e) {
            return $this->errorMessage('Failed to delete weeklyReport: '.$e->getMessage(), 500);
        }
    }
}
