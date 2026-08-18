<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Image;

class PaymentMethodController extends Controller
{
    public function __construct()
    {

    }

    public function getPaymentMethod()
    {
        $data = [
            'title' => 'Semua Metode Pembayaran',
            'data' => PaymentMethod::orderBy('created_at', 'desc')->paginate(20),
        ];
        return view('admin::contents.payment_method.index', $data);
    }

    public function createPaymentMethod()
    {
        $data = [
            'title' => 'Tambah Metode Pembayaran - Manual Transfer',
        ];
        return view('admin::contents.payment_method.create', $data);
    }

    public function storePaymentMethod(Request $request)
    {
        $req = $request->except('_method', '_token', 'submit');

        if ($request->hasFile('logo')) {
            if ($request->file('logo')->isValid()) {
              $destinationPath = 'images/payment_methods/'; // upload path
              $extension = $request->file('logo')->getClientOriginalExtension(); // getting image extension
              $fileName = rand(11111,99999).'.'.$extension; // renaming image
              $request->file('logo')->move($destinationPath, $fileName); // uploading file to given path
              Image::make($destinationPath.$fileName)->resize(500, null, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
              })->save($destinationPath.$fileName);
              $req['logo'] = $fileName;
            }else {
              unset($req['logo']);
            }
        }else {
            unset($req['logo']);
        }

        PaymentMethod::create($req);

    	return redirectMessage(
            route('admin.payment_method.getPaymentMethod'),
            'Successfully Saved !!',
            '',
            'success'
        );
    }

    public function editPaymentMethod($id)
    {
        $data = [
            'title' => 'Edit Metode Pembayaran',
            'data' => PaymentMethod::where('id', $id)->first(),
        ];
        return view('admin::contents.payment_method.edit', $data);
    }

    public function updatePaymentMethod($id, Request $request)
    {

        $req = $request->except('_method', '_token', 'submit');

        if ($request->hasFile('logo')) {
            if ($request->file('logo')->isValid()) {
              $destinationPath = 'images/payment_methods/'; // upload path
              $extension = $request->file('logo')->getClientOriginalExtension(); // getting image extension
              $fileName = rand(11111,99999).'.'.$extension; // renaming image
              $request->file('logo')->move($destinationPath, $fileName); // uploading file to given path
              Image::make($destinationPath.$fileName)->resize(500, null, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
              })->save($destinationPath.$fileName);
              $req['logo'] = $fileName;
            }else {
              unset($req['logo']);
            }
        }else {
            unset($req['logo']);
        }

        PaymentMethod::where('id',$id)->update($req);

    	return redirectMessage(
            route('admin.payment_method.getPaymentMethod'),
            'Successfully Edited !!',
            '',
            'success'
        );
    }

}
