<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request, $id)
    {
        $query = FormSubmission::where('form_id', $id);


        // Search in submission data
        if ($request->search) {

            $query->where(
                'submission_data',
                'like',
                '%' . $request->search . '%'
            );
        }


        $submissions = $query
            ->latest()
            ->paginate(10);


        return response()->json($submissions);
    }



    public function export($id)
    {

        $submissions = FormSubmission::where(
            'form_id',
            $id
        )->get();


        $filename = "submissions.csv";


        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];



        $callback = function () use ($submissions) {


            $file = fopen('php://output', 'w');


            // CSV Header

            fputcsv($file, [
                'ID',
                'Submission Data',
                'IP Address',
                'Created At'
            ]);



            foreach ($submissions as $submission) {


                fputcsv($file, [

                    $submission->id,

                    json_encode(
                        $submission->submission_data
                    ),

                    $submission->ip_address,

                    $submission->created_at

                ]);
            }


            fclose($file);
        };


        return response()->stream(
            $callback,
            200,
            $headers
        );
    }
}
