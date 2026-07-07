<?php

namespace App\Traits;

trait ApiResponseTrait {
    public function success($data = null, $message, $status = 200, $meta = [])
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $status);    
    }

    public function error($message, $status = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
