<?php

namespace App\Repositories\Project;

use App\Models\Provinsi;
use App\Repositories\Base\BaseRepository;

class ProvinsiRepository extends BaseRepository
{
    public function __construct(Provinsi $provinsi)
    {
        $this->model = $provinsi;
    }
}
