<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FormSubmissionController extends Controller
{
    public function store(Request $request, $slug)
    {
        // Find form using public slug

        $form = Form::where('slug', $slug)->first();


        if (!$form) {

            return response()->json([
                'message' => 'Form not found'
            ], 404);
        }


        // Generate validation rules from schema

        $rules = [];


        foreach ($form->schema['sections'] as $section) {

            foreach ($section['fields'] as $field) {


                $fieldRules = [];


                if ($field['required'] ?? false) {

                    $fieldRules[] = 'required';
                }


                switch ($field['type']) {


                    case 'email':

                        $fieldRules[] = 'email';

                        break;


                    case 'number':

                        $fieldRules[] = 'numeric';

                        break;


                    case 'file':

                        $fieldRules[] = 'file';

                        break;
                }


                if (!empty($field['validation'])) {


                    if (!empty($field['validation']['minLength'])) {

                        $fieldRules[] =
                            'min:' . $field['validation']['minLength'];
                    }


                    if (!empty($field['validation']['maxLength'])) {

                        $fieldRules[] =
                            'max:' . $field['validation']['maxLength'];
                    }
                }


                $rules[$field['key']] = $fieldRules;
            }
        }



        // Validate submitted data

        $validator = Validator::make(
            $request->data,
            $rules
        );


        if ($validator->fails()) {


            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }



        // Save submission

        $submission = FormSubmission::create([

            'form_id' => $form->id,

            'submission_data' => $request->data,

            'ip_address' => $request->ip(),

            'user_agent' => $request->userAgent(),

        ]);



        return response()->json([

            'message' => 'Form submitted successfully',

            'submission' => $submission

        ], 201);
    }
}
