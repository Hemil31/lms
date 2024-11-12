<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogServices;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;

/**
 * ActivityLogController
 *
 * Handles requests related to activity logs.
 */
class ActivityLogController extends Controller
{

    use JsonResponseTrait;

    public function __construct(
        protected ActivityLogServices $activityLogServices
    ) {
        //
    }

    /**
     * Index method
     *
     * This method ActivityLog data from the database.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $data = $this->activityLogServices->getAllBook();
            return $this->successResponse($data, 'message.successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during fetch' . $e->getMessage(), statusCode: 500);
        }
    }

}
