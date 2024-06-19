<?php

namespace App\Repositories\Contracts;

interface PropertyRepositoryInterface
{

    public function filterData($request);

    public function storeData($request);

    public function updateData($request, $developer);

    public function updateMetaData($request, $developer);
}
