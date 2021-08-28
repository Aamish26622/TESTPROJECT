<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    public function store(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'website' => 'required|exists:websites,id'
        ]);
        if ($validations->fails()) {
            return collect([
                'status' => false,
                'errors' => $validations->errors()
            ]);
        }

        $posted = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'website_id' => $request->website
        ]);
        if (!$posted) {
            return collect([
                'status' => false,
                'errors' => collect([
                    'message' => 'Something went wrong while posting...'
                ])
            ]);
        }

        return collect([
            'status' => true,
            'data' => [
                'message' => 'Successfully Posted...',
                'data' => $posted
            ]
        ]);
    }
}
