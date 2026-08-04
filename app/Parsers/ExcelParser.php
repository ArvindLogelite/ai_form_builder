<?php

namespace App\Parsers;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelParser
{
    public function parse($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());

        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray();

        $schema = [

            "title" => pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            ),

            "sections" => [

                [
                    "id" => uniqid(),
                    "title" => "Imported Section",
                    "fields" => []

                ]

            ]

        ];

        foreach ($rows as $index => $row) {

            if ($index == 0) {
                continue;
            }

            if (empty($row[0])) {
                continue;
            }

            $schema["sections"][0]["fields"][] = [

                "id" => uniqid(),

                "key" => str()->slug($row[0], "_"),

                "label" => $row[0],

                "type" => $row[1] ?: "text",

                "required" => strtolower($row[2]) == "yes",

                "options" => isset($row[3])
                    ? explode(",", $row[3])
                    : [],

                "placeholder" => "",

                "helpText" => "",

                "defaultValue" => "",

                "validation" => []

            ];
        }

        return $schema;
    }
}
