<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('feedback.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'ขอบคุณสำหรับความคิดเห็นของคุณ!');
    }
    public function index()
    {
        $feedbacks = Feedback::latest()->paginate(10);
        return view('feedback.index', compact('feedbacks'));
    }
}
