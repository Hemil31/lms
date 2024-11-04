<?php

namespace App\Repositories;
use App\Models\ActivityLog;

class ActivityLogRepository extends BaseRepository
{

    /**
     * Summary of __construct
     */
    public function __construct(ActivityLog $activityLog)
    {
        $this->model = $activityLog;
    }

}
