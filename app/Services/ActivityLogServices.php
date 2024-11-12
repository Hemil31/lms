<?php
namespace App\Services;
use App\Repositories\ActivityLogRepository;

/**
 * ActivityLogServices
 *
 * Provides services related to Activity Log.
 */
class ActivityLogServices
{
    /**
     * Constructs a new Activity Log instance.
     *
     * @param \App\Repositories\ActivityLogRepository $ActivityLogRepository The Activity Log repository instance.
     */
    public function __construct(
        protected ActivityLogRepository $activityLogRepository
    ) {
        //
    }


    /**
     * Retrieves all ActivityLog.
     *
     * @return mixed
     */
    public function getAllBook()
    {
        return $this->activityLogRepository->paginate();
    }
}
