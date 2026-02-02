<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriber;
use Mail;
use App\Mail\EmailManager;

class NewsletterController extends Controller
{



    public function emailstore(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);
//        dd($validated);
        Subscriber::insert([
            'email' => $request->email,
        ]);

//        Subscriber::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thanks for subscribing to our newsletter 🎉',
        ], 201);
    }


}
