<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'schema',
        'status'
    ];

    protected $casts = [
        'schema' => 'array',
    ];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
