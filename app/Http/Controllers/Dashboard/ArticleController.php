<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Dashboard\ArticleRequest;
use App\Models\Article;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Auth;

class ArticleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.content_management'),
        ['only' => ['index','create', 'edit', 'update', 'destroy']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = Article::with('user')
        ->applyFilters($request->only(['status']))
        ->orderBy('id','desc')
        ->get();

        return view('dashboard.contentManagement.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.contentManagement.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        try{
            $article = new Article;
            $article->title = $request->title;
            $article->author = $request->author;
            $article->article_type = $request->article_type;
            $article->article_event = $request->article_event;
            $article->mediaOrder = $request->mediaOrder;
            $article->status = $request->status;
            $article->short_content = $request->short_content;
            $article->content = $request->content;
            $article->meta_title = $request->meta_title;
            $article->meta_keywords = $request->meta_keywords;
            $article->meta_description = $request->meta_description;
            $article->publish_at = $request->publish_at;
            $article->user_id = Auth::user()->id;
            $article->article_additional_video = $request->article_additional_video;
            
            if ($request->hasFile('mainImage')) {
                $img =  $request->file('mainImage');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $article->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'articleFiles');
            }
            
            if ($request->hasFile('additionalImage')) {
                $img =  $request->file('additionalImage');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $article->addMediaFromRequest('additionalImage')->usingFileName($imageName)->toMediaCollection('additionalImages', 'articleFiles');
            }
            
            
            if ($request->has('gallery')) {
                foreach($request->gallery as $key=>$img)
                {
                    if(array_key_exists("file", $img) && $img['file']){
                        $title = $img['title'] ?? $request->name;
                        $order =  $img['order'] ?? null;
        
                        $article->addMedia( $img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                            ->toMediaCollection('imageGalleries', 'articleFiles');   
                    }
                }
                
            }
            
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $article->is_approved = config('constants.approved' );
                $article->approval_id = Auth::user()->id;
                
            }else{
                $article->is_approved = config('constants.requested' );
            }
            
            
            $article->save();
            $article->article_banner = $article->mainImage;
            $article->article_additional_banner = $article->additionalImage;
            $article->save();
            
            return response()->json([
                'success' => true,
                'message'=> 'Article has been created successfully.',
                'redirect' => route('dashboard.articles.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.articles.index'),
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        if($article->article_type === 'Blog'){
            return redirect()->route('blog', $article->slug);
        }else{
            return redirect()->route('news', $article->slug);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        return view('dashboard.contentManagement.articles.edit',compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        try{
            $article->title = $request->title;
            $article->author = $request->author;
            $article->article_type = $request->article_type;
            $article->article_event = $request->article_event;
            $article->mediaOrder = $request->mediaOrder;
            $article->status = $request->status;
            $article->short_content = $request->short_content;
            $article->content = $request->content;
            $article->meta_title = $request->meta_title;
            $article->meta_keywords = $request->meta_keywords;
            $article->meta_description = $request->meta_description;
            $article->publish_at = $request->publish_at;
            $article->user_id = Auth::user()->id;
            $article->article_additional_video = $request->article_additional_video;
            $article->generateSlug();
            
            if ($request->hasFile('mainImage')) {
                $article->clearMediaCollection('mainImages');

                $img =  $request->file('mainImage');
                $imgExt = $img->getClientOriginalExtension();

                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $article->addMediaFromRequest('mainImage')->usingFileName($imageName)->toMediaCollection('mainImages', 'articleFiles');

            }
            
             if ($request->hasFile('additionalImage')) {
                $article->clearMediaCollection('additionalImages');
                 
                $img =  $request->file('additionalImage');
                $imgExt = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->title).'.'.$imgExt;
                $article->addMediaFromRequest('additionalImage')->usingFileName($imageName)->toMediaCollection('additionalImages', 'articleFiles');
            }
            
            if ($request->has('gallery')) {
                foreach ($request->gallery as $img) {
                        $title = $img['title'] ?? $request->name;
                        $order =  $img['order'] ?? null;
                        
                        if ($img['old_gallery_id'] > 0) {
                            
                            $mediaItem = Media::find($img['old_gallery_id']);
                            $mediaItem->setCustomProperty('title', $title);
                            $mediaItem->setCustomProperty('order', $order);
                            $mediaItem->save();
                            
                        } else {
                            if(array_key_exists("file", $img) && $img['file']){
                                $article->addMedia( $img['file'])
                                ->withCustomProperties([
                                    'title' => $title,
                                    'order' => $order
                                ])
                                ->toMediaCollection('imageGalleries', 'articleFiles');
                        }
                    }
                }
            }
            if(in_array(Auth::user()->role, config('constants.isAdmin'))){
                $article->approval_id = Auth::user()->id;
                
                if(in_array($request->is_approved, ["approved", "rejected"]) ){
                    $article->is_approved = $request->is_approved;
                }
            }else{
                $article->is_approved = "requested";
                $article->approval_id = null;
            }
            $article->updated_by = Auth::user()->id;

            $article->save();
            
            $article->article_banner = $article->mainImage;
            $article->article_additional_banner = $article->additionalImage;
            $article->save();
            
            return response()->json([
                'success' => true,
                'message'=> 'Article has been updated successfully.',
                'redirect' => route('dashboard.articles.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.articles.index'),
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            Article::find($id)->delete();

            return redirect()->route('dashboard.articles.index')->with('success','Article has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.articles.index')->with('error',$error->getMessage());
        }


    }
    
    public function mediaDestroy(Article $article, $media)
    {
        try{
            $article->deleteMedia($media);
            return redirect()->route('dashboard.articles.edit', $article->id)->with('success','Article Image has been deleted successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.articles.edit', $article->id)->with('error',$error->getMessage());
        }
    }
}
