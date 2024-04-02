<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\AmenityRequest;
use App\Http\Resources\{
    MediaListResource,
    SingleMediaResource
};
use App\Models\{
    Article,
    WebsiteSetting
};
use Auth;
use DB;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        try {

            $collection = Article::active()->approved()->orderByRaw('ISNULL(publish_at)')->orderBy('publish_at', 'desc');
            //$collection = Article::active()->approved()->latest();
            $medias = clone $collection;
            $news = clone $collection;
            $blogs = clone $collection;
            $awards = clone $collection;
            $celebrations = clone $collection;
            $events = clone $collection;

            return $this->success(
                'medias',
                [
                    'all' => MediaListResource::collection($medias->paginate(100))->response()->getData(true),
                    'news' => MediaListResource::collection($news->news()->paginate(100))->response()->getData(true),
                    'blogs' => MediaListResource::collection($blogs->blogs()->paginate(100))->response()->getData(true),
                    'awards' => MediaListResource::collection($awards->awards()->paginate(100))->response()->getData(true),
                    'celebrations' => MediaListResource::collection($celebrations->celebrations()->paginate(100))->response()->getData(true),
                    'events' => MediaListResource::collection($events->where('article_type', 'Sales Event')->paginate(100))->response()->getData(true),
                ],
                200
            );
        } catch (\Exception $exception) {

            return $this->failure($exception->getMessage(), $exception->getCode());
        }
    }

    public function singleMediaMeta($slug)
    {
        try {
            if (Article::where('slug', $slug)->exists()) {
                $article = DB::table('articles')->select('meta_title', 'title', 'meta_description', 'meta_keywords')->where('slug', $slug)->first();
                $singleArticle = (object)[];

                if ($article->meta_title) {
                    $singleArticle->meta_title = $article->meta_title;
                } else {
                    $singleArticle->meta_title = WebsiteSetting::getSetting('website_name') ? WebsiteSetting::getSetting('website_name') : '';
                }
                if ($article->meta_description) {
                    $singleArticle->meta_description = $article->meta_description;
                } else {
                    $singleArticle->meta_description = WebsiteSetting::getSetting('description') ? WebsiteSetting::getSetting('description') : '';
                }

                if ($article->meta_keywords) {
                    $singleArticle->meta_keywords = $article->meta_keywords;
                } else {
                    $singleArticle->meta_keywords = WebsiteSetting::getSetting('keywords') ? WebsiteSetting::getSetting('keywords') : '';
                }

                return $this->success('Single Article Meta', $singleArticle, 200);
            } else {
                return $this->success('Single Article Meta', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }

    public function singleMediaDetail($slug)
    {
        try {
            if (Article::where('slug', $slug)->exists()) {
                $media = Article::where('slug', $slug)->first();
                $media = new SingleMediaResource($media);

                $similarArticles = Article::where('slug', '!=', $slug)->where('article_type', $media->article_type)->orderByRaw('ISNULL(mediaOrder)')->orderBy('mediaOrder', 'asc')->take(4)->get();
                $similarArticles = MediaListResource::collection($similarArticles);

                return $this->success('Single Article', [
                    'media' => $media,
                    'similarMedia' => $similarArticles,
                    'allMedia' =>  MediaListResource::collection(Article::where('article_type', '!=', $media->article_type)->orderByRaw('ISNULL(mediaOrder)')->orderBy('mediaOrder', 'asc')->take(4)->get())
                ], 200);
            } else {
                return $this->success('Single Article', [], 200);
            }
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
