<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $posts = Posts::latest()->paginate(100);
            return response()->json([
                'status' => 'success',
                'posts' => $posts
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error in fetching posts'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|string',
            'content' => 'required|min:10|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            Posts::create($validator->validated());
            return response()->json(['message' => 'Post created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error in creating post' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        //
        try {
            $post = Posts::findOrFail($id);
            return response()->json(['post' => $post], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        dd($request->all());
        //
        $post = Posts::findOrFail($id);
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255/|string',
            'content' => 'required|min:10|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $post->update($validator->all());
            return response()->json(['message' => 'Post created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error in creating post'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            $post = Posts::findOrFail($id);
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting post'], 500);
        }
    }
}
