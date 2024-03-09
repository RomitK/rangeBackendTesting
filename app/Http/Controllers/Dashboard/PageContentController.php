<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\GalleryRequest;
use Illuminate\Support\Str;
use App\Models\{
    Banner,
    Faq,
    Counter,
    Partner,
    PageContent,
    Guide
};
use Auth;
use App\Http\Requests\Dashboard\PageContentRequest;
use DB;

class PageContentController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:' . config('constants.Permissions.languages'),
            [
                'only' => [
                    'index', 'create', 'edit', 'update', 'destroy',
                    'homePage', 'homeContentStore', 'aboutContentStore', 'ceoContentStore', 'aboutPage',
                    'aboutGalleryStore', 'aboutGalleryDestroy', 'rentPage', 'resalePage', 'factFigurePage',
                    'aboutDubaiPage', 'whyInvestPage', 'sellerGuidePage', 'dubaiGuidePage', 'termConditionPage', 'relocatingToDubaiPage',
                    'privacyPolicyPage', 'communitiesPage', 'developersPage', 'faqsPage', 'offPlanPage', 'propertiesPage'
                ]
            ]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function homePage()
    {
        $content = null;
        $title = null;
        $description = null;
        $banners = Banner::home()->latest()->get();
        $counters = Counter::home()->latest()->get();
        if (PageContent::home()->exists()) {
            $content = PageContent::home()->first();
            $title = $content->title;
            $description = $content->description;
        };
        return view('dashboard.pageContents.home', compact('banners', 'content', 'counters', 'title', 'description'));
    }
    public function homeContentStore(PageContentRequest $request)
    {
        try {
            if (PageContent::home()->exists()) {
                $content = PageContent::home()->first();
            } else {
                $content = new PageContent;
            }
            $content->page_name = config('constants.home.name');
            $content->title = $request->title;
            $content->description = $request->description;
            $content->user_id = Auth::user()->id;
            $content->save();

            return response()->json([
                'success' => true,
                'message' => 'Content has been created successfully.',
                'redirect' => route(config('constants.home.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route(config('constants.home.route')),
            ]);
        }
    }
    public function dubaiGuidePage()
    {

        $guides  = Guide::latest()->get();
        $content = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();
        return view('dashboard.pageContents.dubaiGuide', compact('content', 'guides'));
    }
    public function homeStore(Request $request)
    {
        try {

            $content = PageContent::WherePageName(config('constants.home.name'))->first();

            if ($request->hasFile('brochure')) {
                $content->clearMediaCollection('brochure');
                $img =  $request->file('brochure');
                $ext = $img->getClientOriginalExtension();
                $imageName = 'About Dubai Brochure.' . $ext;
                $content->addMediaFromRequest('brochure')->usingFileName($imageName)->toMediaCollection('brochure', 'pageContentFiles');
            }

            $content->save();
            return response()->json([
                'success' => true,
                'message' => 'Content has been created successfully.',
                'redirect' => route(config('constants.home.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route(config('constants.sellerGuide.route')),
            ]);
        }
    }
    public function aboutContentStore(PageContentRequest $request)
    {
        try {
            if (PageContent::about()->exists()) {
                $content = PageContent::about()->first();
            } else {
                $content = new PageContent;
            }
            $content->page_name = config('constants.about.name');
            $content->title = $request->title;
            $content->less_description = $request->less_description;
            $content->more_description = $request->more_description;
            $content->video_iframe = $request->video_iframe;
            if ($request->hasFile('image')) {
                if (PageContent::about()->exists()) {
                    $content->clearMediaCollection('images');
                }
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug(config('constants.about.name')) . '_image.' . $ext;
                $content->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'pageContentFiles');
            }

            if ($request->hasFile('video')) {
                if (PageContent::about()->exists()) {
                    $content->clearMediaCollection('videos');
                }
                $video =  $request->file('video');
                $videoExt = $video->getClientOriginalExtension();
                $videoName =  Str::slug(config('constants.about.name')) . '.' . $videoExt;
                $content->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'pageContentFiles');
            }
            $content->user_id = Auth::user()->id;
            $content->save();
            return redirect()->route(config('constants.about.route'))->with('success', 'Content has been created successfully.');
            //     return response()->json([
            //         'success' => true,
            //         'message'=> 'Content has been created successfully.',
            //         'redirect' => route(config('constants.about.route')),
            //     ]);
        } catch (\Exception $error) {
            return redirect()->route(config('constants.about.route'))->with('error', $error->getMessage());
            // return response()->json([
            //     'success' => false,
            //     'message'=> $error->getMessage(),
            //     'redirect' => route(config('constants.about.route')),
            // ]);
        }
    }
    public function ceoContentStore(PageContentRequest $request)
    {
        try {
            if (PageContent::ceo()->exists()) {
                $content = PageContent::ceo()->first();
            } else {
                $content = new PageContent;
            }
            $content->page_name = config('constants.ceo.name');
            $content->title = $request->title;
            $content->description = $request->description;
            $content->video_iframe_1 = $request->ceo_video_iframe;
            if ($request->hasFile('image')) {
                if (PageContent::ceo()->exists()) {
                    $content->clearMediaCollection('images');
                }
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug(config('constants.ceo.name')) . '_image.' . $ext;

                $content->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'pageContentFiles');
            }

            if ($request->hasFile('video')) {
                if (PageContent::ceo()->exists()) {
                    $content->clearMediaCollection('videos');
                }
                $video =  $request->file('video');
                $videoExt = $video->getClientOriginalExtension();
                $videoName =  Str::slug(config('constants.ceo.name')) . '.' . $videoExt;
                $content->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'pageContentFiles');
            }
            $content->user_id = Auth::user()->id;
            $content->save();

            return response()->json([
                'success' => true,
                'message' => 'Content has been created successfully.',
                'redirect' => route(config('constants.ceo.route')),
            ]);
            //return redirect()->route(config('constants.ceo.route'))->with('success','Content has been created successfully.');
        } catch (\Exception $error) {
            // dd($error->getMessage());
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route(config('constants.ceo.route')),
            ]);
            //return redirect()->route(config('constants.ceo.route'))->with('error',$error->getMessage());
        }
    }
    public function aboutPage()
    {
        $title = null;
        $description = null;
        $image = null;
        $video = null;
        $title = null;
        $less_description = null;
        $more_description = null;
        $ceoMessageTitle = null;
        $ceoMessage = null;
        $ceoImage = null;
        $ceoVideo = null;
        $gallery = [];
        $video_iframe = null;
        $ceo_video_iframe = null;

        $partners = Partner::latest()->get();
        if (PageContent::about()->exists()) {
            $content = PageContent::about()->first();
            $title = $content->title;
            $less_description = $content->less_description;
            $more_description = $content->more_description;
            $image = $content->image;
            $video = $content->video;
            $gallery = $content->gallery;
            $video_iframe = $content->video_iframe;
        };
        if (PageContent::ceo()->exists()) {
            $ceo = PageContent::ceo()->first();
            $ceoMessageTitle = $ceo->title;
            $ceoMessage = $ceo->description;
            $ceoImage = $ceo->image;
            $ceoVideo = $ceo->video;
            $ceo_video_iframe = $ceo->video_iframe_1;
        }
        return view('dashboard.pageContents.about', compact('video_iframe', 'ceo_video_iframe', 'ceoMessageTitle', 'ceoMessage', 'ceoImage', 'ceoVideo', 'partners', 'title', 'image', 'video', 'gallery', 'more_description', 'less_description'));
    }
    public function careerPage()
    {
        $counters = Counter::career()->latest()->get();
        return view('dashboard.pageContents.career', compact('counters'));
    }
    public function aboutGalleryStore(GalleryRequest $request)
    {
        try {
            if (PageContent::about()->exists()) {
                $content = PageContent::about()->first();
            } else {
                $content = new PageContent;
            }
            $content->page_name = config('constants.about.name');
            if ($request->images != null && count($request->images) > 0) {
                foreach ($request->images as $image) {
                    $content->addMedia($image)->toMediaCollection('Gallery', 'pageContentFiles');
                }
            }

            $content->user_id = Auth::user()->id;
            $content->save();

            return redirect()->route(config('constants.about.route'))->with('success', 'Images has been added successfully.');
        } catch (\Exception $error) {
            return redirect()->route(config('constants.about.route'))->with('error', $error->getMessage());
        }
    }
    public function aboutGalleryDestroy($gallery)
    {
        try {
            $content = PageContent::about()->first();
            $content->deleteMedia($gallery);
            return redirect()->route(config('constants.about.route'))->with('success', 'Image has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route(config('constants.about.route'))->with('error', $error->getMessage());
        }
    }
    public function rentPage()
    {
        $contents = PageContent::WherePageName(config('constants.rent.name'))->latest()->get();
        $faqs = Faq::WherePageName(config('constants.rent.name'))->latest()->get();
        return view('dashboard.pageContents.rent', compact('contents', 'faqs'));
    }
    public function resalePage()
    {
        $contents = PageContent::WherePageName(config('constants.resale.name'))->latest()->get();
        $faqs = Faq::WherePageName(config('constants.resale.name'))->latest()->get();
        return view('dashboard.pageContents.resale', compact('contents', 'faqs'));
    }
    public function propertiesPage()
    {
        $contents = PageContent::WherePageName(config('constants.properties.name'))->latest()->get();
        $faqs = Faq::WherePageName(config('constants.properties.name'))->latest()->get();
        return view('dashboard.pageContents.properties', compact('contents', 'faqs'));
    }
    public function offPlanPage()
    {
        $contents = PageContent::WherePageName(config('constants.offPlan.name'))->latest()->get();
        $faqs = Faq::WherePageName(config('constants.offPlan.name'))->latest()->get();
        return view('dashboard.pageContents.offPlan', compact('contents', 'faqs'));
    }
    public function faqsPage()
    {
        $faqs = Faq::WherePageName(config('constants.faqs.name'))->latest()->get();
        return view('dashboard.pageContents.faqs', compact('faqs'));
    }
    public function developersPage()
    {
        $contents = PageContent::WherePageName(config('constants.developers.name'))->latest()->get();
        return view('dashboard.pageContents.developers', compact('contents'));
    }
    public function communitiesPage()
    {
        $contents = PageContent::WherePageName(config('constants.communities.name'))->latest()->get();
        return view('dashboard.pageContents.communities', compact('contents'));
    }
    public function privacyPolicyPage()
    {
        $contents = PageContent::WherePageName(config('constants.privacyPolicy.name'))->latest()->get();
        return view('dashboard.pageContents.privacyPolicy', compact('contents'));
    }
    public function termConditionPage()
    {
        $contents = PageContent::WherePageName(config('constants.termCondition.name'))->latest()->get();
        return view('dashboard.pageContents.termCondition', compact('contents'));
    }
    public function buyerGuidePage()
    {
        $contents = PageContent::WherePageName(config('constants.buyerGuide.name'))->latest()->get();
        return view('dashboard.pageContents.buyerGuide', compact('contents'));
    }
    public function sellerGuidePage()
    {
        $content = PageContent::WherePageName(config('constants.sellerGuide.name'))->first();
        return view('dashboard.pageContents.sellerGuide', compact('content'));
    }
    public function relocatingToDubaiPage()
    {
        $contents = PageContent::WherePageName(config('constants.relocatingToDubai.name'))->latest()->get();
        return view('dashboard.pageContents.relocatingToDubai', compact('contents'));
    }
    public function whyInvestPage()
    {
        $contents = PageContent::WherePageName(config('constants.whyInvest.name'))->latest()->get();
        return view('dashboard.pageContents.whyInvest', compact('contents'));
    }
    public function aboutDubaiPage()
    {
        $contents = PageContent::WherePageName(config('constants.aboutDubai.name'))->latest()->get();
        return view('dashboard.pageContents.aboutDubai', compact('contents'));
    }
    public function factFigurePage()
    {
        $contents = PageContent::WherePageName(config('constants.factFigure.name'))->latest()->get();
        return view('dashboard.pageContents.factFigure', compact('contents'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($page)
    {
        return view('dashboard.pageContents.contents.create', compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function sellerGuideStore(Request $request)
    {

        try {

            $content = PageContent::WherePageName(config('constants.sellerGuide.name'))->first();

            if ($request->hasFile('sellerGuide')) {
                $content->clearMediaCollection('sellerGuide');
                $img =  $request->file('sellerGuide');
                $ext = $img->getClientOriginalExtension();
                $imageName = 'SellerGuide.' . $ext;
                $content->addMediaFromRequest('sellerGuide')->usingFileName($imageName)->toMediaCollection('sellerGuide', 'pageContentFiles');
            }

            $content->save();
            return response()->json([
                'success' => true,
                'message' => 'Content has been created successfully.',
                'redirect' => route(config('constants.sellerGuide.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route(config('constants.sellerGuide.route')),
            ]);
        }
    }
    public function dubaiGuideStore(Request $request)
    {
        try {
            $content = PageContent::WherePageName(config('constants.dubaiGuide.name'))->first();

            if ($request->hasFile('goldenVisa')) {
                $content->clearMediaCollection('goldenVisa');
                $img =  $request->file('goldenVisa');
                $ext = $img->getClientOriginalExtension();
                $imageName = 'GoldenVisaGuide.' . $ext;
                $content->addMediaFromRequest('goldenVisa')->usingFileName($imageName)->toMediaCollection('goldenVisa', 'pageContentFiles');
            }

            if ($request->hasFile('buyerGuide')) {
                $content->clearMediaCollection('buyerGuide');
                $img =  $request->file('buyerGuide');
                $ext = $img->getClientOriginalExtension();
                $imageName = 'BuyerGuide.' . $ext;
                $content->addMediaFromRequest('buyerGuide')->usingFileName($imageName)->toMediaCollection('buyerGuide', 'pageContentFiles');
            }

            if ($request->hasFile('propertiesGuide')) {
                $content->clearMediaCollection('propertiesGuide');
                $img =  $request->file('propertiesGuide');
                $ext = $img->getClientOriginalExtension();
                $imageName = 'LuxuryPropertiesGuide.' . $ext;
                $content->addMediaFromRequest('propertiesGuide')->usingFileName($imageName)->toMediaCollection('propertiesGuide', 'pageContentFiles');
            }
            $content->save();
            return response()->json([
                'success' => true,
                'message' => 'Content has been created successfully.',
                'redirect' => route(config('constants.dubaiGuide.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route(config('constants.dubaiGuide.route')),
            ]);
        }
    }
    public function store(PageContentRequest $request)
    {
        try {
            $content = new PageContent;
            $content->page_name = $request->page_name;
            $content->title = $request->title;
            $content->less_description = $request->less_description;
            $content->more_description = $request->more_description;
            $content->description = $request->description;
            if ($request->hasFile('image')) {
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($request->page_name) . '_image.' . $ext;
                $content->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'pageContentFiles');
            }

            if ($request->hasFile('video')) {
                $video =  $request->file('video');
                $videoExt = $video->getClientOriginalExtension();
                $videoName =  Str::slug($request->page_name) . '.' . $videoExt;
                $content->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'pageContentFiles');
            }

            $content->user_id = Auth::user()->id;
            $content->save();

            return response()->json([
                'success' => true,
                'message' => 'Content has been created successfully.',
                'redirect' => route(config('constants.' . $request->page_name . '.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route(config('constants.' . $request->page_name . '.route')),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($page, PageContent $content)
    {
        return view('dashboard.pageContents.contents.edit', compact('page', 'content'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageContentRequest $request, $page, PageContent $content)
    {
        try {
            $content->page_name = $page;
            $content->title = $request->title;
            $content->less_description = $request->less_description;
            $content->more_description = $request->more_description;
            $content->description = $request->description;
            if ($request->hasFile('image')) {
                $content->clearMediaCollection('images');
                $img =  $request->file('image');
                $ext = $img->getClientOriginalExtension();
                $imageName =  Str::slug($page) . '_image.' . $ext;
                $content->addMediaFromRequest('image')->usingFileName($imageName)->toMediaCollection('images', 'pageContentFiles');
            }

            if ($request->hasFile('video')) {
                $content->clearMediaCollection('videos');
                $video =  $request->file('video');
                $videoExt = $video->getClientOriginalExtension();
                $videoName =  Str::slug($page) . '.' . $videoExt;
                $content->addMediaFromRequest('video')->usingFileName($videoName)->toMediaCollection('videos', 'pageContentFiles');
            }

            $content->user_id = Auth::user()->id;
            $content->save();
            return response()->json([
                'success' => true,
                'message' => 'Content has been updated successfully.',
                'redirect' => route(config('constants.' . $request->page_name . '.route')),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => $error->getMessage(),
                'redirect' => route(config('constants.' . $request->page_name . '.route')),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($page, PageContent $content)
    {
        try {
            $content->delete();
            return redirect()->route(config('constants.' . $page . '.route'))->with('success', 'Content has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route(config('constants.' . $page . '.route'))->with('error', $error->getMessage());
        }
    }
}
