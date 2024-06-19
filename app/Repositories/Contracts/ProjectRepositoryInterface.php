<?php

namespace App\Repositories\Contracts;

interface ProjectRepositoryInterface
{

    public function filterData($request);

    public function storeData($request);

    public function updateData($request, $project);

    public function updateMetaData($request, $project);

    public function subProjectStore($request, $project);

    public function subProjectUpdate($request, $project, $subProject);
}
