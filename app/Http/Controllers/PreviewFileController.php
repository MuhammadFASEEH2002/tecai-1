<?php

namespace App\Http\Controllers;

use App\Models\HomeWork;
use App\Models\TContent;
use App\Models\TeacherContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PreviewFileController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;
        $content = TContent::find($id);
        $content_type = $content->content_type;

        if ($content_type == 'Flash') {
            return view('home.viewer.flash', compact('content_type', 'id'));
        }
        if ($content_type == 'Pdf') {
            return view('home.viewer.pdf', compact('content_type', 'id'));
        }
        if ($content_type == 'Video') {
            return view('home.viewer.video', compact('content_type', 'id'));
        }
        if ($content_type == 'GIF') {
            return view('home.viewer.gif', compact('content_type', 'id'));
        }
        if ($content_type == 'Ppt') {
            return view('home.viewer.ppt', compact('content_type', 'id'));
        }

        // return $this->downloadFile($request);
    }
    public function TeacherViewFile(Request $request)
    {
        $content = TeacherContent::find($request->id);
        $content_type = $content->content_type;
        $url = Storage::disk('local')->temporaryUrl($content->content_link, now()->addMinutes(5));
        return view('home.preview', compact('content_type', 'url'));
    }
    public function downloadFile(Request $request)
    {
        $content = TContent::find($request->id);
        $content_type = $content->content_type;

        if ($content_type == 'Pdf') {
            $filePath = Storage::disk('local')->path($content->content_link);
            $fileName = 'your-custom-filename.pdf';
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
        } elseif ($content_type == 'Video') {
            $filePath = Storage::disk('local')->path($content->content_link);
            $fileName = 'your-custom-filename.mp4';
            $headers = [
                'Content-Type' => 'video/mp4',
            ];
        } elseif ($content_type == 'Flash') {
            $filePath = Storage::disk('local')->path($content->content_link);
            $fileName = 'your-custom-filename.swf';
            $headers = [
                'Content-Type' => 'application/x-shockwave-flash',
            ];
        } elseif ($content_type == 'GIF') {
            $filePath = Storage::disk('local')->path($content->content_link);
            $fileName = 'sample.gif';
            $headers = [
                'Content-Type' => 'image/gif',
            ];
        } elseif ($content_type == 'Ppt') {
            $filePath = Storage::disk('local')->path($content->content_link);
            $fileName = 'sample.pptx';
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ];
        }

        return Response::download($filePath, $fileName, $headers);
    }
    public function ApiStudentHomeWorkPreview(Request $request)
    {
        $content = HomeWork::find($request->id);
        $image = $content->image;

        $filePath = Storage::disk('local')->path($image);
        $fileName = 'homework.png';
        $headers = [
            'Content-Type' => 'image/png',
        ];

        return Response::download($filePath, $fileName, $headers);
    }
}
