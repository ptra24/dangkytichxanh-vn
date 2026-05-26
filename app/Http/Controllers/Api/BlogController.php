<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = BlogPost::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    /**
     * Display the specified blog post by slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Bài viết không tồn tại.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post,
        ]);
    }
}
