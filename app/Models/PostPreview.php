<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostPreview extends Model {
    protected $fillable  = ['id', 'art_id',  'preview_url', 'created_at'];
}
