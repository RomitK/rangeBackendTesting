<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Project,
    Community,
    WebsiteSetting,
    PageTag,
    Property,
    Developer,
    Accommodation,
    Category,
    Lead,
    Testimonial,
    Faq,
    PageContent,
    OtpVerification
};

use App\Http\Resources\{
    MetaResource
};

class MetaController extends Controller
{
    public function homeMeta(Request $request, $pageName)
    {
        try {
            $pagemeta =  PageTag::where('page_name', $pageName)->first();;
            return $this->success('Home Data Meta',  new MetaResource($pagemeta), 200);
        } catch (\Exception $exception) {
            return $this->failure($exception->getMessage());
        }
    }
}
