<?php

namespace App\Parsers;

use PhpOffice\PhpWord\IOFactory;

class WordParser
{
    public function parse($file)
    {
        $phpWord = IOFactory::load($file->getPathname());

        $schema = [
            "title" => pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            ),
            "sections" => []
        ];

        $currentSection = [
            "id" => uniqid(),
            "title" => "Main Section",
            "fields" => []
        ];

        $lines = [];

        $unparsed = [];

        foreach ($phpWord->getSections() as $section) {

            foreach ($section->getElements() as $element) {

                if (!method_exists($element, "getText")) {
                    continue;
                }

                $text = trim($element->getText());

                if ($text !== "") {
                    $lines[] = $text;
                }
            }
        }

        $i = 0;

        while ($i < count($lines)) {

            $text = trim($lines[$i]);

            /*
             * Skip Empty
             */

            if ($text === "") {
                $i++;
                continue;
            }

            /*
             * Section Detection
             */

            if (
                str_contains(strtolower($text), "information") ||
                strtolower($text) === "declaration"
            ) {

                if (!empty($currentSection["fields"])) {
                    $schema["sections"][] = $currentSection;
                }

                $currentSection = [
                    "id" => uniqid(),
                    "title" => $text,
                    "fields" => []
                ];

                $i++;
                continue;
            }

            /*
             * Dropdown / Radio Detection
             */

            if (
                isset($lines[$i + 1]) &&
                isset($lines[$i + 2])
            ) {

                $next1 = trim($lines[$i + 1]);
                $next2 = trim($lines[$i + 2]);

                if (
                    $this->looksLikeOption($next1) &&
                    $this->looksLikeOption($next2)
                ) {

                    $options = [];

                    $j = $i + 1;

                    while (
                        isset($lines[$j]) &&
                        $this->looksLikeOption($lines[$j])
                    ) {

                        $options[] = trim($lines[$j]);

                        $j++;
                    }

                    $currentSection["fields"][] = [

                        "id" => uniqid(),

                        "key" => str()->slug($text, "_"),

                        "label" => $text,

                        "type" => $this->guessChoiceType($text),

                        "required" => false,

                        "placeholder" => "",

                        "helpText" => "",

                        "defaultValue" => "",

                        "options" => $options,

                        "validation" => []

                    ];

                    $i = $j;

                    continue;
                }
            }

            /*
             * Very long text → Unparsed
             */

            if (strlen($text) > 120) {

                $unparsed[] = $text;

                $i++;

                continue;
            }

            /*
             * Normal Field
             */

            $currentSection["fields"][] = [

                "id" => uniqid(),

                "key" => str()->slug($text, "_"),

                "label" => $text,

                "type" => $this->guessType($text),

                "required" => false,

                "placeholder" => "",

                "helpText" => "",

                "defaultValue" => "",

                "options" => [],

                "validation" => []

            ];

            $i++;
        }

        if (!empty($currentSection["fields"])) {
            $schema["sections"][] = $currentSection;
        }

        return [

            "schema" => $schema,

            "unparsed" => $unparsed

        ];
    }

    private function guessType($text)
    {
        $text = strtolower($text);

        if (str_contains($text, "email")) {
            return "email";
        }

        if (str_contains($text, "phone")) {
            return "text";
        }

        if (str_contains($text, "date")) {
            return "date";
        }

        if (str_contains($text, "resume")) {
            return "file";
        }

        if (str_contains($text, "password")) {
            return "password";
        }

        return "text";
    }

    private function guessChoiceType($text)
    {
        $text = strtolower($text);

        if (
            str_contains($text, "gender") ||
            str_contains($text, "marital")
        ) {
            return "radio";
        }

        return "dropdown";
    }

    private function looksLikeOption($text)
    {
        return strlen(trim($text)) < 30;
    }
}
