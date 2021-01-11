<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Category;
use App\Http\Resources\BlogResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blogQuery = auth()->user()->blogs()->with('categories')->latest();

        if ($request->filled('status')) {
            $blogQuery->where('status', $request->status);
        }

        $blogs= $blogQuery->get();

        return BlogResource::collection($blogs);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $input = $request->all();
        $input['slug'] = str_slug($request->title);

        if($request->filled('published_at')) {
            $input['published_at'] = Carbon::parse($request->published_at);
        }

        $blog = auth()->user()->blogs()->create($input);

        if($request->filled('categories')) {

            $categoryIds= $this->createCategories($request->categories);

            $blog->categories()->sync($categoryIds);

        }

        return new BlogResource($blog->load('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return new BlogResource($blog);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        if (Gate::denies('update-blog', $blog)) {
            abort(401, "Sorry Not Authorized");
        }

        $input = $request->all();

        if ($request->filled('title')) {
            $input['slug'] = str_slug($request->title);
        }

        if ($request->filled('published_at')) {
            $input['published_at'] = Carbon::parse($request->published_at);
        }

        $blog->update($input);

        if ($request->filled('categories')) {

            $categoryIds = $this->createCategories($request->categories);

            $blog->categories()->sync($categoryIds);
        }
       

        return new BlogResource($blog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if(Gate::denies('update-blog', $blog)){
            abort(401, "Sorry Not Authorized");
        }

        $blog->delete();

        return response(['message' => 'blog deleted!']);
    }
}
