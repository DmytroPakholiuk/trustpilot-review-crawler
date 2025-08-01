<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $fillable = [
        'path',
        'external_url',
        'is_downloaded',
    ];
}
