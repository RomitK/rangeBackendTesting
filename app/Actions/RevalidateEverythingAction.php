<?php

namespace App\Actions;

use App\Actions\RevalidationHandler\CampaignRevalidationHandlerAction;
use App\Actions\RevalidationHandler\WebsiteRevalidationHandlerAction;

class RevalidateEverythingAction
{
    public function __construct(
        protected WebsiteRevalidationHandlerAction $websiteAction,
        protected CampaignRevalidationHandlerAction $campaignAction,
        protected RevalidateDataAction $dataAction
    ){}
    public function execute():bool
    {
        $datas = $this->dataAction->execute();

        $set_of_tags = [] ;

        foreach($datas as $key => $data){
            $set_of_tags[] = $data['tag'] ;

            if($data['slug_available']){
                $set_of_tags[] = $data['tag'] . ':*'  ;
            }
        }
        
        $this->websiteAction->executeSetsOfTags($set_of_tags) ;
        $this->campaignAction->executeSetsOfTags($set_of_tags) ;

        return true ;
    }
}