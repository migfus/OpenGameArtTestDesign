<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $title
 * @property string|null $image_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Affiliate extends \Eloquent {}
}

namespace App\Models{
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
	class Art extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ArtCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $art_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Art $art
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment whereArtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtComment whereUserId($value)
 * @mixin \Eloquent
 */
	class ArtComment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $art_id
 * @property int $art_preview_category_id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ArtPreviewCategory $art_preview_category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview whereArtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview whereArtPreviewCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreview whereUrl($value)
 * @mixin \Eloquent
 */
	class ArtPreview extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreviewCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreviewCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreviewCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreviewCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreviewCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreviewCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtPreviewCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ArtPreviewCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ArtType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ArtType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property int $user_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereUserId($value)
 * @mixin \Eloquent
 */
	class Collection extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $art_id
 * @property string $name
 * @property string $file_url
 * @property int $download_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereArtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereDownloadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class File extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Art> $arts
 * @property-read int|null $arts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUrl($value)
 * @mixin \Eloquent
 */
	class License extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostPreview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostPreview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostPreview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostPreview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostPreview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostPreview whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PostPreview extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property int|null $user_id
 * @property string $title
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RecentForum whereUserId($value)
 * @mixin \Eloquent
 */
	class RecentForum extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $url_username
 * @property string $username
 * @property string|null $image_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUrlUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUsername($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $value
 * @property string $domain
 * @property string $path
 * @property string $secure
 * @property string $http_only
 * @property string $expires
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereHttpOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereSecure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserSession whereValue($value)
 * @mixin \Eloquent
 */
	class UserSession extends \Eloquent {}
}

