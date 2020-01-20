<?php

namespace App;

use App\Notifications\DeckPublished;
use App\Scopes\PublishedScope;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Deck extends Model implements HasMedia
{
    use Sluggable, Searchable, HasMediaTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'title', 'description', 'lang_source_id', 'lang_target_id', 'visibility', 'slug'];
    protected $with = ['media'];
    public const PUBLIC_VISIBILITY = ['public' => 'anybody can see'];
    public const UNLISTED_VISIBILITY = ['unlisted' => 'only with link'];
    public const PRIVATE_VISIBILITY = ['private' => 'only you'];

    public static function visibilities() : array
    {
        return [self::PUBLIC_VISIBILITY, self::UNLISTED_VISIBILITY, self::PRIVATE_VISIBILITY];
    }

    public static function visibilityNames() : array
    {
        return collect(self::visibilities())->flatMap(fn ($options) => [
            array_key_first($options),
        ])->toArray();
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::created(function ($deck) {
            if ($deck->visibility === key(self::PUBLIC_VISIBILITY)) {
                Notification::send(NotificationUser::all(), new DeckPublished($deck));
            }
        });

        parent::boot();
        static::addGlobalScope(new PublishedScope);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'unique' => true,
                'source' => 'title',
                'onUpdate' => true,
            ],
        ];
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('main')
            ->singleFile();
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function scopePublished($query)
    {
        return $query->where('visibility', '=', key(self::PUBLIC_VISIBILITY));
    }

    public function scopeOrderByVisibility($query)
    {
        return $query->orderBy(DB::raw("case when visibility = '".key(self::PUBLIC_VISIBILITY)."' then 1 when visibility = '".key(self::UNLISTED_VISIBILITY)."' then 2 when visibility = '".key(self::PRIVATE_VISIBILITY)."' then 3 end"));
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function sourceLanguage()
    {
        return $this->hasOne('App\Language', 'id', 'lang_source_id');
    }

    public function targetLanguage()
    {
        return $this->hasOne('App\Language', 'id', 'lang_target_id');
    }
}
