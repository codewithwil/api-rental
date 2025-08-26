<?php

namespace App\Services\History\ActivityLog;

use App\{
    Repositories\History\ActivityLog\ActivityLogRepositoryInterface,
    Traits\ApiResponse,
};

class ActivityLogService
{
    use ApiResponse;

    public function __construct(protected ActivityLogRepositoryInterface $activityLog) {}

    public function index($perPage = 10)
    {
        $logs = $this->activityLog->getAll($perPage);

        return $this->successResponse([
            'activityLog' => $logs
        ]);
    }
}
