<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model {
    protected $fillable  = ['id', 'art_id',  'file_url', 'created_at'];
}
