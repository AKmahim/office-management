<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class DownloadPageController extends Controller
{
    //
    public function index(Request $request)
    {
        //dd($request->all());
        $content_id = $request->content_id;
        $contents = Content::where('content_id', $content_id)->first();
        if (!$contents) {
            return redirect()->back()->with('error', 'Photo/Video not found');
        }

        return view('event.nestle.download', compact('contents'));
    }
}
