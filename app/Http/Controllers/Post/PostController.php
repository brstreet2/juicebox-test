<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\Post\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function list(Request $request)
    {
        return $this->postService->lists($request->query());
    }

    public function show(int $id)
    {
        return $this->postService->show($id);
    }

    public function store(PostRequest $request)
    {
        return $this->postService->createPost($request->validated());
    }

    public function update(int $id, PostRequest $request)
    {
        return $this->postService->updatePost($id, $request->validated());
    }

    public function destroy(int $id)
    {
        return $this->postService->deletePost($id);
    }
}
