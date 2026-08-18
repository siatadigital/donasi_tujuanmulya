<?php

namespace App\Repositories\Kota;

use App\Models\Kota;
use App\Repositories\Base\BaseRepository;

class KotaRepository extends BaseRepository
{
    public function __construct(Kota $kota)
    {
        $this->model = $kota;
    }

    public function getAllKotaByProvinsi($id)
    {
    	return $this->model->where('provinsi_id', '=', $id)->get();
    }

    public function isRelatedProvinsi($id_prov, $id_kota){
    	$n = $this->model
    			->where('provinsi_id', $id_prov)
    			->where('id', $id_kota)
    			->count();
       	if($n == 1) return true;
       	else return false;
    }
}
