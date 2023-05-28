<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function getPosts()
    {
        $id = Auth::user()->id;
        $post = Post::where('user_id', $id)->orderBy('created_at', 'DESC')->get();

        return response()->json([
            'posts' =>$post ,
            'user' => $id,
        ]);
    }

    public function addPost(Request $request)
    {
        $request->validate([
            'post_image' => 'nullable',
            'post_url' => 'sometimes',
            'statut'=>'sometimes',
        ]);
        $user = Auth::user();
        if ($request->hasFile("post_image")) {
            $img = $request->file("post_image");// Uploadedfile;
            $imageName = Str::random() . '.' . $img->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('user/post/image', $img, $imageName);
            Post::create([
                'post_image' => $imageName,
                'post_url' => asset("storage/" . $path),
                'user_id' => $user->id,
                'likes' => 201,
                "statut" => $request->statut
            ]);
            return response()->json([
                'message' => 'post add successfully',
                'user' => $user->id,
            ]);
        } else {
            $img = $request->file("post_image");// Uploadedfile;
            $imageName = Str::random() . '.' . $img->getClientOriginalName();
            $path = Storage::disk('public')->putFileAs('user/post/image', $img, $imageName);
            Post::update([
                'post_image' => $imageName,
                'post_url' => asset("storage/" . $path),
                'user_id' => $user,
                'likes' => 201
            ]);
            return response()->json([
                'message' => 'post add successfully'
            ]);
        }
        return response()->json([
            'message' => 'not good'
        ]);
    }
    public function deletePost($id)
    {
        $post = Post::findorfail($id);
        $post->delete();
        return response()->json([
            'status' => 200,
            'message' => 'post delete successfully'
        ]);
    }
}
