<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'slug',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots_index',
        'robots_follow',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'json_ld',
        'head_scripts',
        'sitemap_priority',
        'sitemap_frequency',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'robots_index' => 'boolean',
            'robots_follow' => 'boolean',
        ];
    }

    public function redirects(): HasMany
    {
        return $this->hasMany(PageRedirect::class);
    }

    public function robotsContent(): string
    {
        return implode(', ', [
            $this->robots_index ? 'index' : 'noindex',
            $this->robots_follow ? 'follow' : 'nofollow',
        ]);
    }
}
