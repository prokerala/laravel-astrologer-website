<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::published()
                    ->orderBy('published_at', 'desc')
                    ->paginate(12);
        
        return view('blog.index', compact('blogs'));
    }
    
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
                   ->published()
                   ->firstOrFail();
        
        $relatedBlogs = Blog::published()
                           ->where('id', '!=', $blog->id)
                           ->inRandomOrder()
                           ->limit(3)
                           ->get();
        
        return view('blog.show', compact('blog', 'relatedBlogs'));
    }
}
