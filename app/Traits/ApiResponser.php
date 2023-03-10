<?php

namespace App\Traits;

trait ApiResponser
{

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            // 'data'    => base64_encode(json_encode($result)),
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
