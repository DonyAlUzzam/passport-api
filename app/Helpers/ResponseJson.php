<?php


namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use App\Constants\HttpStatusCodes;


class ResponseJson
{
    public static function success($data = [], $message = 'Success', $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'status_code' => HttpStatusCodes::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error($message = 'Error', $status = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'status_code' => HttpStatusCodes::HTTP_BAD_REQUEST,
            'message' => $message,
            'data' => null,
        ], $status);
    }

    public static function unAuth($message = 'Error', $status = 401): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'status_code' => HttpStatusCodes::HTTP_UNAUTHORIZED,
            'message' => $message,
            'data' => null,
        ], $status);
    }

    public static function forbidden($message = 'Error', $status = 403): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'status_code' => HttpStatusCodes::HTTP_FORBIDDEN,
            'message' => $message,
            'data' => null,
        ], $status);
    }

    public static function notFound($message = 'Error', $status = 404): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'status_code' => HttpStatusCodes::HTTP_NOT_FOUND,
            'message' => $message,
            'data' => null,
        ], $status);
    }

}
