<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Art extends Model {
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable  = ['id', 'user_id',  'title', 'created_at', 'content', 'art_category_id', 'favorites_count'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function artCategory() {
        return $this->belongsTo(ArtCategory::class);
    }
}
