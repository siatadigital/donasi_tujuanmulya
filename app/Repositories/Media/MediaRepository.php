<?php

namespace App\Repositories\Media;

use App\Models\Media;
use App\Repositories\Base\BaseRepository;

class MediaRepository extends BaseRepository
{
    public function __construct(Media $media)
    {
        $this->model = $media;
    }
}
