<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends BaseController
{

    public function index()
    {
        $category = Category::all();
        return $this->handleRespondSuccess('data', $category);
    }

    public function store(CategoryRequest $request)
    {
        $user = Auth::id();
        $category = new Category();
        $image = $request->image;
        $category->fill($request->all());
        if ($image) {
            $imageName = Str::random(10);
            $imagePath = $image->storeAs('public/upload/' . date('Y/m/d'), $imageName);
            $imageUrl = asset(Storage::url($imagePath));
            $category->url_image = $imageUrl;
        }
        $category->user_id = $user;
        $category->slug = Str::slug($request->name);
        $category->save();
        return $this->handleRespondSuccess('create success', $category);
    }

    public function update(CategoryRequest $request, Category $category)
    {

        $image = $request->image;
        if (!$image) {
            $category->update($request->all());
            return $this->handleRespondSuccess('update success', $category);
        }
        $imageName = Str::random(10);
        $path = 'public' . Str::after($category->url_image, 'storage');
        Storage::delete($path);
        $imagePath = $image->storeAs('public/upload/' . date('Y/m/d'), $imageName);
        $imageUrl = asset(Storage::url($imagePath));
        $category->fill($request->all());
        $category->url_image = $imageUrl;
        $category->save();
        return $this->handleRespondSuccess('update success', $category);
    }

    public function destroy(Category $category)
    {
        $path = 'public' . Str::after($category->url_image, 'storage');
        $category->delete();
        Storage::delete($path);
        return $this->handleRespondSuccess('delete success', []);
    }
}
