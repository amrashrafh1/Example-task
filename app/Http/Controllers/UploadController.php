<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Traits\uploadFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    use uploadFiles;

    public function upload_video(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'media' => ['required', 'mimes:mp4,mov,ogg,qt','max:20480'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        return response(['path' => $this->UploadSingleFile($request->media, 'employees/videos')]);
    }

    public function upload_picture(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'media' => ['required', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $newName = $this->UploadSingleFile($request->media, 'employees/pictures');

        $imageFullPath = public_path('storage/employees/pictures/'.$newName);
        $img = \Image::make($imageFullPath);

        /* insert watermark at bottom-right 10px */
        $img->insert(public_path('images/logo.png'), 'bottom-right', 10, 10);

        $img->save(public_path('storage/employees/pictures/'.$newName));

        return response(['path' =>  $newName]);

    }
}
