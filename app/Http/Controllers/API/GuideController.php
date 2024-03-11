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

            //@if(in_array($tag->id, $guide->tags()->pluck('tag_category_id')->toArray()))

            $collection = Guide::query();
            if (isset($request->keyword)) {

                $question = $request->keyword;
                $tagCategoriesIds = TagCategory::where(['type' => config('constants.guide'), 'status' => config('constants.active')])->where('name', 'like', "%$question%")->pluck('id')->toArray();
                // $tagCategoriesIds = TagCategory::where(['type' => config('constants.guide'), 'status' => config('constants.active')])->where('name', $question)->pluck('id')->toArray();
                $collection->whereHas('tags', function ($query) use ($tagCategoriesIds) {
                    $query->whereIn('tag_category_id', $tagCategoriesIds);
                });
            }
            $guides =  $collection->with('tags')->active()->approved()->orderByRaw('ISNULL(orderBy)')->orderBy('orderBy', 'asc')->get();

            $guides = GuideResource::collection($guides);
            return $this->success('Guide Data', $guides, 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
