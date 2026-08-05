<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Services\ImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function import(
        Request $request,
        ImportService $importService
    ) {

        $request->validate([
            'file' => 'required|mimes:docx,xlsx,xls|max:10240',
        ]);

        try {

            $result = $importService->parse(
                $request->file("file")
            );

            return response()->json([
                "message" => "File parsed successfully.",
                "schema" => $result["schema"],
                "unparsed" => $result["unparsed"],
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function save(Request $request)
    {

        $request->validate([
            'schema' => 'required|array',
            'schema.title' => 'required|string',
            'schema.sections' => 'required|array|min:1',
        ]);

        $schema = $request->schema;

        $form = Form::create([

            'title' => $schema['title'] ?? 'Imported Form',

            'slug' => str()->slug(
                $schema['title'] ?? 'Imported Form'
            ) . '-' . uniqid(),

            'description' => 'Imported from document',

            'schema' => $schema,

            'status' => 'draft'

        ]);

        return response()->json([
            'message' => 'Form imported successfully.',
            'form' => $form,
        ]);
    }
}
