<?php

namespace App\Jobs;

use App\Models\AiJob;
use App\Models\Form;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateAIForm implements ShouldQueue
{
    use Queueable;


    public function __construct(
        public AiJob $aiJob
    ) {}



    public function handle(): void
    {

        try {


            $this->aiJob->update([
                'status' => 'processing'
            ]);


            $prompt = strtolower($this->aiJob->prompt);

            $fields = [];

            // Default Fields

            $fields[] = [
                "id" => uniqid(),
                "key" => "name",
                "type" => "text",
                "label" => "Full Name",
                "placeholder" => "Enter Full Name",
                "required" => true,
                "validation" => []
            ];

            $fields[] = [
                "id" => uniqid(),
                "key" => "email",
                "type" => "email",
                "label" => "Email",
                "placeholder" => "Enter Email",
                "required" => true,
                "validation" => []
            ];

            // Prompt Based Fields

            if (str_contains($prompt, "phone")) {
                $fields[] = [
                    "id" => uniqid(),
                    "key" => "phone",
                    "type" => "phone",
                    "label" => "Phone Number",
                    "placeholder" => "Enter Phone Number",
                    "required" => true,
                    "validation" => []
                ];
            }

            if (str_contains($prompt, "resume")) {
                $fields[] = [
                    "id" => uniqid(),
                    "key" => "resume",
                    "type" => "file",
                    "label" => "Resume",
                    "placeholder" => "",
                    "required" => true,
                    "validation" => []
                ];
            }

            if (str_contains($prompt, "skill")) {
                $fields[] = [
                    "id" => uniqid(),
                    "key" => "skills",
                    "type" => "textarea",
                    "label" => "Skills",
                    "placeholder" => "Enter Skills",
                    "required" => false,
                    "validation" => []
                ];
            }

            if (str_contains($prompt, "experience")) {
                $fields[] = [
                    "id" => uniqid(),
                    "key" => "experience",
                    "type" => "textarea",
                    "label" => "Experience",
                    "placeholder" => "Enter Experience",
                    "required" => false,
                    "validation" => []
                ];
            }

            if (str_contains($prompt, "address")) {
                $fields[] = [
                    "id" => uniqid(),
                    "key" => "address",
                    "type" => "textarea",
                    "label" => "Address",
                    "placeholder" => "Enter Address",
                    "required" => false,
                    "validation" => []
                ];
            }

            if (str_contains($prompt, "gender")) {
                $fields[] = [
                    "id" => uniqid(),
                    "key" => "gender",
                    "type" => "radio",
                    "label" => "Gender",
                    "required" => false,
                    "options" => ["Male", "Female", "Other"],
                    "validation" => []
                ];
            }

            if (
                str_contains($prompt, "dob") ||
                str_contains($prompt, "birth") ||
                str_contains($prompt, "date")
            ) {
                $fields[] = [
                    "id" => uniqid(),
                    "key" => "dob",
                    "type" => "date",
                    "label" => "Date of Birth",
                    "required" => false,
                    "validation" => []
                ];
            }

            $schema = [
                "title" => "AI Generated Form",
                "sections" => [
                    [
                        "id" => uniqid(),
                        "title" => "Main Section",
                        "fields" => $fields
                    ]
                ]
            ];



            $form = Form::create([

                'title' => 'AI Generated Form',

                'slug' => str()->slug(
                    'AI Generated Form'
                ) . '-' . uniqid(),

                'description' => $this->aiJob->prompt,

                'schema' => $schema,

                'status' => 'draft'

            ]);



            $this->aiJob->update([

                'form_id' => $form->id,

                'status' => 'completed',

                'response' => json_encode($schema)

            ]);
        } catch (\Exception $e) {


            $this->aiJob->update([

                'status' => 'failed',

                'error' => $e->getMessage()

            ]);
        }
    }
}
