<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function __construct()
    {

    }

    public function getCreate()
    {
        $data = [
            'title' => 'Blasting Email',
        ];
        return view('admin::contents.email.create', $data);
    }

}
