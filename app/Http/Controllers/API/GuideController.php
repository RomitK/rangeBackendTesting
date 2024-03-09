<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Tag,
    TagCategory,
    Guide
};
use App\Http\Resources\{
    GuideResource
};

class GuideController extends Controller
{
    public function allGuides(Request $request)
    {
        try {

            $collection = Guide::query();
            if (isset($request->keyword)) {
                $question = $request->keyword;
                $collection->where('question', 'like', "%$question%");
            }
            $guides =  $collection->active()->approved()->orderByRaw('ISNULL(orderBy)')->orderBy('orderBy', 'asc')->get();
            $guides = GuideResource::collection($guides);
            return $this->success('Guide Data', $guides, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
