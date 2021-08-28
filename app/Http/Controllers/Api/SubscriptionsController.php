<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubscriptionsController extends Controller
{
    public function subscribe(Request $request)
    {
        $validations = Validator::make($request->all(), [
            'email' => 'required|exists:users',
            'website' => 'required|exists:websites,id'
        ]);
        if ($validations->fails()) {
            return collect([
                'status' => false,
                'errors' => $validations->errors()
            ]);
        }
        if ($request->email != Auth::user()->email) {
            return collect([
                'status' => false,
                'errors' => collect([
                    'email' => 'This email does not belong to you.'
                ])
            ]);
        }

        if ($this->alreadySubscribedToWebsite(Auth::id(), $request->website)) {
            return collect([
                'status' => false,
                'errors' => [
                    'subscription' => 'You have already subscribed to this website.'
                ]
            ]);
        }

        $subscribed = Subscription::create([
            'user_id' => Auth::id(),
            'website_id' => $request->website
        ]);
        if (!$subscribed) {
            return collect([
                'status' => false,
                'errors' => [
                    'subscription' => 'Could not subscribe to the website.'
                ]
            ]);
        }
        return collect([
            'status' => true,
            'data' => [
                'message' => 'Successfully Subscribed...'
            ]
        ]);
    }

    public function alreadySubscribedToWebsite($id, $website)
    {
        return Subscription::where('user_id', $id)->where('website_id', $website)->count();
    }
}
