<?php

namespace App\Repositories\History\ActivityLog;

use App\{
    Models\History\Activity\ActivityLog
};

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function getAll()
    {
        return ActivityLog::with('user')->get();
    }
}
