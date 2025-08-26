<?php

namespace App\Repositories\History\ActivityLog;

use App\{
    Models\History\Activity\ActivityLog
};

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function getAll($perPage = 10)
    {
        return ActivityLog::with('user')->latest()->paginate($perPage);
    }
}
