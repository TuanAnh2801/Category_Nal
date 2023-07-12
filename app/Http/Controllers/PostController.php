<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends BaseController
{

    public function show(Post $post)
    {
        $data = $post->load('categories');
        return $this->handleRespondSuccess('data', $data);

    }

    public function store(PostRequest $request, Post $post)
    {
        $user = Auth::id();
        $categoryId = $request->category;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->status = $request->status;
        $post->type = $request->type;
        $post->user_id = $user;
        $post->slug = Str::slug($request->title);
        $post->save();
        foreach ($categoryId as $category) {
            $post->categories()->attach($category);
        }

        return $this->handleRespondSuccess('create success', $post);
    }

    public function update(PostRequest $request, Post $post)
    {
        $categoryId = $request->category;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->status = $request->status;
        $post->type = $request->type;
        $post->slug = Str::slug($request->title);
        $post->save();
        $post->categories()->detach();
        foreach ($categoryId as $category) {
            $post->categories()->attach($category);
        }
        return $this->handleRespondSuccess('update success', $post);

    }


    public function destroy(Post $post)
    {
        $post->categories()->detach($post->id);
        $post->delete();
        return $this->handleRespondSuccess('delete success', []);

    }
}
