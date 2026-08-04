<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateAIForm;
use App\Models\AiJob;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        $job = AiJob::create([
            'prompt' => $request->prompt,
            'status' => 'pending',
        ]);

        GenerateAIForm::dispatch($job);

        return response()->json([
            'message' => 'AI generation started.',
            'job_id' => $job->id,
        ]);
    }


    public function edit(Request $request)
    {
        return response()->json([
            'message' => 'AI edit is not implemented yet.',
        ]);
    }
}
