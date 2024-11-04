<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * JsonResponseTrait for generating JSON responses.
 */
trait JsonResponseTrait
{

    /**
     * Generate a JSON response for successful operations.
     *
     * @param  mixed  $data
     * @param  string  $messageKey
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function successResponse($data=null, $messageKey = 'success', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message_code' => $messageKey,
            'message' => __($messageKey),
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Generate a JSON response for errors.
     *
     * @param  string  $message
     * @param  string  $messageKey
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function errorResponse( $messageKey = 'error', $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message_code' => $messageKey,
            'message' => __($messageKey),
        ], $statusCode);
    }

    /**
     * Generate a JSON response for validation errors.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validation
     * @param  string  $messageKey
     * @param  int  $statusCode
     * @return JsonResponse
     */
    public function validationError($validation, $messageKey = 'VALIDATION_ERROR', $statusCode = 422): JsonResponse
    {
        $validationErrors = $validation->errors();
        $errorResponse = [
            'success' => false,
            'message_code' => $messageKey,
            'message' => trans($messageKey),
            'status_code' => $statusCode,
            'errors' => $validationErrors,
        ];
        return response()->json( $errorResponse, $statusCode);
    }

}
