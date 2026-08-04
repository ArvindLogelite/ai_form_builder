<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormRequest;
use App\Models\Form;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function index()
    {
        return response()->json(
            Form::latest()->paginate(10)
        );
    }

    public function store(StoreFormRequest $request)
    {
        $form = Form::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'description' => $request->description,
            'schema' => $request->schema,
            'status' => $request->status ?? 'draft'
        ]);

        return response()->json([
            'message' => 'Form created successfully.',
            'data' => $form
        ], 201);
    }

    public function show(Form $form)
    {
        return response()->json($form);
    }

    public function update(StoreFormRequest $request, Form $form)
    {
        $form->update([
            'title' => $request->title,
            'description' => $request->description,
            'schema' => $request->schema,
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Form updated successfully.',
            'data' => $form
        ]);
    }

    public function destroy(Form $form)
    {
        $form->delete();

        return response()->json([
            'message' => 'Form deleted successfully.'
        ]);
    }

    public function publicForm($slug)
    {
        $form = Form::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($form);
    }
}
