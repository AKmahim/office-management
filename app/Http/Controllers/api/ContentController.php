<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Content;
use App\Models\Event;
use Illuminate\Validation\ValidationException;



class ContentController extends Controller
{

    // generate random 7 digit number for video id 
    protected function generateUniqueVideoId()
    {
        do {
            $randomNumber = mt_rand(1000000, 9999999);
        } while (Content::where('content_id', $randomNumber)->exists());

        return $randomNumber;
    }


   public function StoreContent(Request $request)
    {
        
        try {
            $request->validate([
                'content' => 'required|file|max:20480', // 20MB in KB
                'event_id' => 'required|exists:events,event_id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        if (!$request->hasFile('content')) {
            return response()->json([
                'status' => 400,
                'message' => 'No file uploaded. The file may be too large for the server limits.',
                'php_upload_max_filesize' => ini_get('upload_max_filesize'),
                'php_post_max_size' => ini_get('post_max_size'),
            ], 400);
        }

        $event = Event::where('event_id', $request->event_id)->first();

        // make folder name with event_name and event_id
        $folder_name = Str::slug($event->event_name) . '-' . $event->event_id;
        // create folder if not exists
        $folder_path = public_path('media/content/' . $folder_name);
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0755, true);
        }
        //create folder by date(d-m-Y)
        $date_folder = Carbon::now()->format('d-m-Y');
        $date_folder_path = $folder_path . '/' . $date_folder;
        if (!file_exists($date_folder_path)) {
            mkdir($date_folder_path, 0755, true);
        }
        //full path of the folder
        $full_folder_path = 'media/content/' . $folder_name . '/' . $date_folder . '/';

         // ============== content upload =======
         $content = '';
         if($request->content){
             $temp_content = $request->file('content');
             $name_gen = hexdec(uniqid());
             $content_ext = strtolower($temp_content->getClientOriginalExtension());
             $content_name = $name_gen . '.' . $content_ext;

             // move the content to the folder
             $up_location = 'media/content/' . $folder_name . '/' . $date_folder . '/';
             $content = $up_location.$content_name;
             $temp_content->move($up_location,$content_name);

            // create content id 
            $content_id = $this->generateUniqueVideoId();
            //store the content path into the database
            Content::create([
                'event_id' => $request->event_id,
                'content_id' => $content_id,
                'content_url' => $content,
            ]);
    
            return response()->json([
                'status' => 200,
                'message' => 'Content uploaded Successfully',
                'content_id' => $content_id,
                'content_url' => asset($content),
            ],200);
             
         }
         else{
            return response()->json([
                'status' => 200,
                'message' => 'Content upload fail',
                'content_id' => null,
                'content_url' => null,
            ],200);
         }

        
    }
}
