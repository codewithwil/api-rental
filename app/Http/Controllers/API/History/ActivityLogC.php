<?php

namespace App\Http\Controllers\API\History;

use App\{
    Http\Controllers\Controller,
    Services\History\ActivityLog\ActivityLogService
};

class ActivityLogC extends Controller
{
    public function __construct(protected ActivityLogService $service) {}

    public function index() { return $this->service->index(); }
}
