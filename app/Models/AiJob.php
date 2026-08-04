<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiJob extends Model
{
    protected $fillable = [
        'form_id',
        'prompt',
        'status',
        'response',
        'error'
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
