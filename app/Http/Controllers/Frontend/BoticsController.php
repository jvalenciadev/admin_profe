<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BoticsController extends Controller
{
    // Blogs
    public function index()
    {
        return view('frontend.pages.botics.index');
    }
}
