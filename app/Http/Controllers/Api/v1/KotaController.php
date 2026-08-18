<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Provinsi\ProvinsiRepository;
use App\Repositories\Kota\KotaRepository;
use App\Models\Kota;
use Request;
use Input;

class KotaController extends Controller
{
    public function __construct(KotaRepository $kota)
    {
        $this->kota = $kota;
    }

    public function getKotaByProvinsi(){
        $req = Input::get('provinsi');

        $data = $this->kota->getAllKotaByProvinsi($req);
        $option = '';
        foreach ($data as $row) {
            $option = $option . '<option value="' . $row->id . '">' . $row->kota_name . '</option>';
        }

        return $option;
    }
}
