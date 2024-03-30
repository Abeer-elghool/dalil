<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Download\DownloadRequest;
use App\Models\Download;
use Illuminate\Http\Request;

class DownloadModelController extends Controller
{
    public function download(DownloadRequest $request)
    {
        $userId = auth()->id();
        $downloadableType = "App\\Models\\". $request->downloadable_type;
        $downloadableId = $request->downloadable_id;

        $download = new Download();
        $download->user_id = $userId;
        $download->downloadable_type = $downloadableType;
        $download->downloadable_id = $downloadableId;
        $download->save();

        return response()->json(['status' => 200, 'data' => null, 'message' => 'success']);
    }
}
