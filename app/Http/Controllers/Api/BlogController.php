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

    /**
     * Store a newly created blog post in storage (Admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'image_url' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $slug = \Illuminate\Support\Str::slug($request->title);
        // Check uniqueness of slug
        $count = BlogPost::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $post = BlogPost::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'image_url' => $request->image_url,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo bài viết thành công.',
            'data' => $post
        ], 201);
    }

    /**
     * Update the specified blog post in storage (Admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $post = BlogPost::find($id);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết.'
            ], 404);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'image_url' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $slug = \Illuminate\Support\Str::slug($request->title);
        $count = BlogPost::where('slug', $slug)->where('id', '!=', $id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        $post->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'image_url' => $request->image_url,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bài viết thành công.',
            'data' => $post
        ]);
    }

    /**
     * Remove the specified blog post from storage (Admin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $post = BlogPost::find($id);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài viết.'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa bài viết thành công.'
        ]);
    }
}
