<?php

namespace App\Services;

use App\Parsers\WordParser;
use App\Parsers\ExcelParser;

class ImportService
{
    public function parse($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'docx' => (new WordParser())->parse($file),
            'xlsx', 'xls' => (new ExcelParser())->parse($file),
            default => throw new \Exception('Unsupported file type.')
        };
    }
}
