<?php
namespace App\Services;
use App\Repositories\ActivityLogRepository;


/**
 * BookServices
 *
 * Provides services related to book management.
 */
class ActivityLogServices
{
    /**
     * Constructs a new BookServices instance.
     *
     * @param \App\Repositories\ActivityLogRepository $ActivityLogRepository The book repository instance.
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
        return $this->activityLogRepository->all();
    }
}
