<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\{Community, Stat};
use DB;
use DOMDocument;
use Log;
class CommunityImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{
            DB::beginTransaction();
            foreach($collection as $index=>$data){
                if($index>0){
                    $id = $data[0];
                    $emirates_id = $data[1];
                    $community_name = $data[2];
                    $community_overall = $data[3];
                    $community_facility_description = $data[4];
                    $community_facility_list = $data[5];
                    $community_nearby = $data[6];
                   
                    $community_latitude = $data[7];
                    $community_longitude = $data[8];
                    $community_main_photo = $data[9];
                    
                    $community_breadcrumbs_banner = $data[10];
                    $community_logo = $data[11];
                    $community_status = $data[12];
                    $community_is_hot = $data[13];
                    $community_is_list = $data[14];
                    $community_language = $data[15];
                    $community_linker = $data[16];
                    if($community_language == 'en' &&  $community_status == 'Y')
                    {
                        $alreadyExist = Community::where('name',$community_name)->exists();
                        $community = Community::where('name',$community_name)->exists()?Community::where('name',$community_name)->first():new Community;
                        
                        $community->name = $community_name;
                        $community->emirates = 'Dubai';
                        $community->description = $community_overall;
                        $community->user_id = 1;
                        if($community_main_photo)
                        {
                            $image = 'https://www.uniqueproperties.ae/plugins/images/area/'.$community_main_photo;
                            if($alreadyExist){
                                $community->clearMediaCollection('mainImages');
                            }
                            $community->addMediaFromUrl( $image)->toMediaCollection('mainImages', 'commnityFiles');
                        }
                        $community->save();
                        Log::info("community-id-".$community->id."-row-".$index);
                        
                        // $nearBy = !empty($community_nearby)? $this->html_to_obj($community_nearby):null;
                        // $nearByData = !empty($nearBy)? $nearBy['children'][0]['children']: null;

                        // if(!empty($nearBy) && $nearByData && !empty($nearByData)){
                        //     foreach($nearByData as $key=>$statData){
                        //     if($statData['tag'] === 'p'){
                        //         foreach($statData['children'] as $child){
                        //             if(!empty($child['html'])){
                        //                 $stat = new Stat;
                        //                 $stat->name = $child['html'];
                        //                 $community->stats()->save($stat);
                                    
                        //                 if(!empty($nearByData[$key+1]) && count($nearByData[$key+1])> 0 && $nearByData[$key+1]['children'] && count($nearByData[$key+1]['children'])>0){
                        //                     foreach($nearByData[$key+1]['children'] as $statValue){
                                                
                        //                         if($statValue['tag'] == 'li'){
                        //                             $statDataValue = null;
                        //                             $statDatakey =  preg_split('~(\d+)~',$statValue['html'],0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE )[0];
                        //                             if($statDatakey){
                        //                                 $statDataValue = array_values(array_filter(explode(trim($statDatakey), $statValue['html'])))[0];
                        //                             }
                        //                             $stat->values()->create(['key'=>$statDatakey, 'value'=>$statDataValue]);
                                                    
                        //                         }
                        //                     }
                        //                 }
                        //             }
                        //         }
                        //     }
                        // }
                        // }
                        
                    }
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            echo $e->getMessage();
        }
    }
    function html_to_obj($html) {
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        return $this->element_to_obj($dom->documentElement);
    }
    function element_to_obj($element) {
        $obj = array( "tag" => $element->tagName );
        foreach ($element->attributes as $attribute) {
            $obj[$attribute->name] = $attribute->value;
        }
        foreach ($element->childNodes as $subElement) {
            if ($subElement->nodeType == XML_TEXT_NODE) {
                $obj["html"] = $subElement->wholeText;
            }
            else {
                $obj["children"][] = $this->element_to_obj($subElement);
            }
        }
        return $obj;
    }
}
