<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait RespondsWithHttpStatus
{
    protected function success($message, $data = [], $status = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function failure($message, $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    protected function validationFail($errors = [], $status = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'success' => false,
            'errors' => $errors,
        ], $status);
    }

    protected function paginated($collection, $options = [])
    {
        $response['data'] = $collection->items();
        $response['meta'] = array_merge([
            'total' => $collection->total(),
            'per_page' => $collection->perPage(),
            'current_page' => $collection->currentPage(),
            'last_page' => $collection->lastPage(),
            'options' => $collection->getOptions(),
        ], $options);

        return $response;

    }
}
