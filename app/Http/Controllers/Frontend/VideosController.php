<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Galeria;

class VideosController extends Controller
{
    // Programas
    public function index()
    {
        $videos = Video::get();

        return view('frontend.pages.video.index', compact('videos'));
    }

    public function show($sede_id)
    {

    }

}
