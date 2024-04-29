<?php

namespace App\Http\Controllers\NotificationTest;

use App\Http\Controllers\Controller;
use Faker\Core\File;
use http\Client\Response;
use Illuminate\Http\Request;

class TransllationController extends Controller
{
    public function getForm()
    {
        $form_fields = \Safe\json_decode(\File::get(public_path('global_en.json')));
        return view('test.translation', ['form_fields' => $form_fields]);
    }

    public function submitForm(Request $request)
    {
//        dump($form_fields = \Safe\json_decode(\File::get(public_path('global_en.json'))));
//        dd($request->all());
        $data = $request->all();
        $selected_language = $data['languages'];
        unset($data['_token']);
        unset($data['languages']);


        \File::put(public_path('/translations/' . $selected_language . '.json'), json_encode($data));
        return response()->download(public_path('/translations/' . $selected_language . '.json'));


    }

}
