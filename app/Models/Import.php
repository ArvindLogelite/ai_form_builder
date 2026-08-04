<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'file_name',
        'file_type',
        'status',
        'parsed_schema',
        'error'
    ];

    protected $casts = [
        'parsed_schema' => 'array',
    ];
}
