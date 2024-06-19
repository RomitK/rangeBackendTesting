<?php

namespace App\Repositories\Contracts;

use App\Models\Developer;

interface CommunityRepositoryInterface
{

    public function filterData($request);

    public function storeData($request);

    public function updateData($request, $developer);

    public function updateMetaData($request, $developer);
}
