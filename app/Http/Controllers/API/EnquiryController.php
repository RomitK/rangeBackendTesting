<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Enquiry\EnquiryStoreRequest;
use App\Models\Enquiry;
use Illuminate\Http\JsonResponse;

class EnquiryController extends Controller
{
    public function __invoke(EnquiryStoreRequest $request): JsonResponse {

        $validatedData = $request->validated();
        $validatedData['mobile_country_code'] = '+' . $validatedData['mobile_country_code'];
        Enquiry::query()->create($validatedData);

        return response()->json(['message' => 'Success', 'data' => null], 200);
    }
}
