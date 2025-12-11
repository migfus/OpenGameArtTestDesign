<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentForum extends Model {
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable  = ['id', 'user_id',  'title', 'created_at', 'content'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
