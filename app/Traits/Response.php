<?php

namespace App\Traits;

trait Response
{
    /**
     * responseSuccess
     *
     * @param  mixed $result
     * @param  mixed $message
     * @return void
     */
    public function responseSuccess($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }


    /**
     * responseError
     *
     * @param  mixed $error
     * @param  mixed $errorMessages
     * @param  mixed $code
     * @return void
     */
    public function responseError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'status'  => $code,
            'message' => $error,
        ];

        // put messages errors in data
        $response['data'] = !empty($errorMessages) ? $errorMessages : [];
        // return response
        return response()->json(
            $response,
            $code
        );
    }

}
