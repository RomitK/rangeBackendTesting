<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Article;
use DB;
use DOMDocument;
use Log;

class ArticleImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', 6000);
        try{
            DB::beginTransaction();
            foreach($collection as $index=>$data){
              
                if($index>0){
                    $blog_id = $data[0];
                    $blog_title = $data[1];
                    $blog_linker = $data[2];
                    $blog_description = $data[3];
                    $blog_content = $data[4];
    
                    $mainImage = null;
                    $doc=new DOMDocument();
                    $doc->loadHTML($blog_description);
                    $xml=simplexml_import_dom($doc); // just to make xpath more simple
                    $images = $xml->xpath('//img');
                    if($images && !empty($images)){
                        $mainImage = $images[0]['src'];
                    }
                    
                    $blog_publish_date = $data[5];
                    $blog_category = $data[6];
                    $blog_link = $data[7];
                    $blog_guid = $data[8];
                    $blog_dc_date = $data[9];
                    
                    $blog_author = $data[10];
                    $blog_status = $data[11];
                    $blog_views = $data[12];
                    $blog_featured = $data[13];
                    $blog_language = $data[14];
                  
                  
                    if($blog_language === 'en' &&  $blog_status == 'Y')
                    {
                        $article = Article::where(['title'=> $blog_title, 'article_type'=>'Blog'])->exists()? Article::where(['title'=> $blog_title, 'article_type'=>'Blog'])->first(): new Article;
                        
                        $article->title = $blog_title;
                        $article->author = $blog_author;
                        $article->article_type = 'Blog';
                        $article->publish_at = $blog_publish_date;
                        $article->content = $blog_description;
                        $article->user_id = 1;
                       
                        if ($mainImage) {
                            if(Article::where(['title'=> $blog_title, 'article_type'=>'Blog'])->exists()){
                                $article->clearMediaCollection('mainImages');
                            }
                            
                            $article->addMediaFromUrl($mainImage)->toMediaCollection('mainImages', 'articleFiles');
                        }
                        $article->save();
                        Log::info('$articleId-'.$article->id);
                       
                    }
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
