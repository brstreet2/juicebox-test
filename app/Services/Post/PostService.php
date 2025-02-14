<?php

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function lists(array $filters)
    {
        try {
            $perPage = $filters['per_page'] ?? 10;

            $posts = Post::query()->with('user')->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'status'    => 200,
                'message'   => 'Posts retrieved successfully.',
                'error'     => false,
                'data'      => $posts
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Failed to retrieve posts.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $post = Post::with('user')->find($id);

            if (!$post) {
                return response()->json([
                    'status'    => 404,
                    'message'   => 'Post not found.',
                    'error'     => false,
                    'data'      => null,
                ], 404);
            }

            return response()->json([
                'status'  => 200,
                'message' => 'Post retrieved successfully.',
                'error'   => false,
                'data'    => $post,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Failed to retrieve post.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function createPost(array $data)
    {
        try {
            $data['user_id'] = Auth::id();
            $post = Post::create($data);

            return response()->json([
                'status'    => 201,
                'message'   => 'Post created successfully.',
                'eror'      => false,
                'data'      => $post,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Failed to create post.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function updatePost(int $id, array $data)
    {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status'    => 404,
                    'message'   => 'Post not found.',
                    'error'     => false,
                    'data'      => null,
                ], 404);
            }

            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'status'  => 403,
                    'message' => 'Unauthorized to update this post.',
                    'error'   => true,
                    'data'    => null,
                ], 403);
            }

            $post->update($data);

            return response()->json([
                'status'  => 200,
                'message' => 'Post updated successfully.',
                'error'   => false,
                'data'    => $post,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Failed to create post.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function deletePost(int $id)
    {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status'    => 404,
                    'message'   => 'Post not found.',
                    'error'     => false,
                    'data'      => null,
                ], 404);
            }

            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'status'  => 403,
                    'message' => 'Unauthorized to delete this post.',
                    'error'   => true,
                    'data'    => null,
                ], 403);
            }

            $post->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Post deleted successfully.',
                'error'   => false,
                'data'    => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Failed to delete post.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }
}
