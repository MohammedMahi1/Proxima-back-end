<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Friends\FriendRequest;
use App\Models\Post\Post;
use App\Models\User;
use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $user = Auth::user();
        $id = FriendRequest::where('accepted', true)->value('sender_id');
        $friends = User::where('id', $id)->get();
        return response()->json([
            'user' => $user,
            'friend'=>$friends
        ]);
    }

    public function addImageProfile(Request $request)
    {
        $request->validate([
            'image_profile' => 'nullable',
            'image_url' => 'sometimes',
        ]);
        $user = Auth::user();
        if ($request->hasFile("image_profile")) {
            $exist = Storage::disk('public')->exists("user/image/{$user->image_profile}");
            if ($exist) {
                Storage::disk('public')->delete("user/image/{$user->image_profile}");
                $img = $request->file("image_profile");// Uploadedfile;
                $imageName = Str::random() . '.' . $img->getClientOriginalName();

                $path = Storage::disk('public')->putFileAs('user/image', $img, $imageName);
                $exis = $user->update([
                    'image_profile' => $imageName,
                    'image_url' => asset("storage/" . $path)
                ]);
                if ($exis) {
                    return response()->json([
                        'message' => 'image add successfully'
                    ]);
                }
            } else {
                $img = $request->file("image_profile");// Uploadedfile;
                $imageName = Str::random() . '.' . $img->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('user/image', $img, $imageName);
                $exis = $user->update([
                    'image_profile' => $imageName,
                    'image_url' => asset("storage/" . $path)
                ]);
                if ($exis) {
                    return response()->json([
                        'message' => 'image add successfully'
                    ]);
                }
            }

        }
        return response()->json([
            'message' => 'not good'
        ]);
    }

    public function addCoverProfile(Request $request)
    {
        $request->validate([
            'cover_profile' => 'nullable',
            'cover_url' => 'sometimes',
        ]);
        $user = Auth::user();
        if ($request->hasFile("cover_profile")) {
            $exist = Storage::disk('public')->exists("user/cover/{$user->cover_profile}");
            if ($exist) {
                Storage::disk('public')->delete("user/cover/{$user->cover_profile}");
                $img = $request->file("cover_profile");// Uploadedfile;
                $imageName = Str::random() . '.' . $img->getClientOriginalName();

                $path = Storage::disk('public')->putFileAs('user/cover', $img, $imageName);
                $exis = $user->update([
                    'cover_profile' => $imageName,
                    'cover_url' => asset("storage/" . $path)
                ]);
                if ($exis) {
                    return response()->json([
                        'message' => 'image add successfully'
                    ]);
                }
            } else {
                $img = $request->file("cover_profile");// Uploadedfile;
                $imageName = Str::random() . '.' . $img->getClientOriginalName();
                $path = Storage::disk('public')->putFileAs('user/cover', $img, $imageName);
                $exis = $user->update([
                    'cover_profile' => $imageName,
                    'cover_url' => asset("storage/" . $path)
                ]);
                if ($exis) {
                    return response()->json([
                        'message' => 'image add successfully'
                    ]);
                }
            }

        }
        return response()->json([
            'message' => 'not good'
        ]);
    }
    public function sendFriendRequest(Request $request)
    {
        $senderId = $request->user()->id;
        $receiverId = $request->input('receiver_id');

        $friendRequest = FriendRequest::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
        ]);
        $friendRequest->save();
        return response()->json(['message' => 'Friend request sent successfully'], 200);
    }

    public function acceptFriendRequest(Request $request)
    {
        $friendRequestId = $request->input('friend_request_id');

        $friendRequest = FriendRequest::findOrFail($friendRequestId);
        $friendRequest->accepted = true;
        $friendRequest->save();

        return response()->json(['message' => 'Friend request accepted'], 200);
    }

    public function rejectFriendRequest(Request $request)
    {
        $friendRequestId = $request->input('friend_request_id');

        $friendRequest = FriendRequest::findOrFail($friendRequestId);
        $friendRequest->delete();

        return response()->json(['message' => 'Friend request rejected'], 200);
    }

    public function getFriendRequests(Request $request)
    {
        $user = $request->user();
        $friendRequests = $user->receivedFriendRequests()->with('sender')->get();
        return response()->json(['friendRequests' => $friendRequests]);
    }
}
