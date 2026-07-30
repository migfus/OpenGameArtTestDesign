<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    HasMany
};

/**
 * @property string $id
 * @property int|null $user_id
 * @property int $art_category_id
 * @property string $title
 * @property string|null $content
 * @property int $favorites_count
 * @property int $comments_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ArtCategory $art_category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtComment> $art_comments
 * @property-read int|null $art_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ArtPreview> $art_previews
 * @property-read int|null $art_previews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\File> $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\License> $licenses
 * @property-read int|null $licenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereArtCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereCommentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereFavoritesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Art whereUserId($value)
 * @mixin \Eloquent
 */
class Art extends Model {
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'arts';

    protected $fillable  = [
        'id',
        'user_id',
        'title',
        'created_at',
        'content',
        'art_category_id',
        'favorites_count',
        'updated_at',
        'comments_count',
        'image_preview'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function art_category(): BelongsTo {
        return $this->belongsTo(ArtCategory::class);
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(Tag::class);
    }

    public function art_previews(): HasMany {
        return $this->hasMany(ArtPreview::class);
    }

    public function files(): HasMany {
        return $this->hasMany(File::class);
    }

    public function art_comments(): HasMany {
        return $this->hasMany(ArtComment::class);
    }

    public function licenses(): BelongsToMany {
        return $this->belongsToMany(License::class);
    }
}
