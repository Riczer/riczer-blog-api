<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function index(Request $request) {
        // return Post::all();
        return PostResource::collection(Post::all());
    }

    public function store(Request $request) {
        // if($request->ajax()) {
            try {                

                //Validation
                $this->validate($request, [
                    'title' => 'required|string|max:255',
                    'description' => 'required|string|max:255',
                ]);

                //Slug
                $slug = preg_replace('/[^A-zA-z0-9]/','-', $request->title);
                $slug = strtolower($slug);

                //Save new entry
                $post = new Post;
                $post->user_id = 1;
                $post->title = $request->title;
                $post->slug = $slug;
                $post->description = $request->description;
                $post->save();

                //Return response
                return response()->json([
                    'Message' => 'Ok',
                    'Post' => new PostResource($post)
                ]);
            } catch (ValidationException $error) {
                return response()->json(
                    $error->validator->errors()
                );
            }
        // }
    }

    public function update(Request $request, Post $post) {
        if($request->ajax()) {
            try {
                //Validation
                $this->validate($request, [
                    'title' => 'required|string|max:255',
                    'description' => 'required|string|max:255',
                ]);

                //Slug
                $slug = preg_replace('/[^A-zA-z0-9]/','-', $request->title);
                $slug = strtolower($slug);

                //Save new entry
                $post->user_id = 1;
                $post->title = $request->title;
                $post->slug = $slug;
                $post->description = $request->description;
                $post->save();

                //Return response
                return response()->json([
                    'Message' => 'Ok',
                    'Post' => new PostResource($post)
                ]);
            } catch (ValidationException $error) {
                return response()->json(
                    $error->validator->errors()
                );
            }
        }
    }

    public function show(Post $post) {
        return new PostResource(Post::findOrFail($post->id)); 
    }

    public function destroy(Post $post) {
        $post->delete();

        return response()->json([
            'Message' => 'Ok',
            'Post' => new PostResource($post)
        ]);
    }
}
