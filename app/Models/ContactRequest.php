<?php

namespace App\Models;

use App\Support\Attribution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * A message sent through the contact page, plus where the sender came from.
 *
 * This is the one kind of visitor data the website keeps itself. It is not
 * CoreX data (no demo login hangs off it, nothing can drift) — it is the
 * marketing site's own lead log, kept so that enquiries can be attributed to
 * the search term, ad or referrer that produced them.
 */
class ContactRequest extends Model
{
    /**
     * What the form lets the sender say the message is about. The key is
     * stored; the label is shown.
     *
     * @var array<string, string>
     */
    public const TOPICS = [
        'general' => 'General question',
        'sales' => 'Sales & pricing',
        'support' => 'Support',
        'partnership' => 'Partnership',
        'media' => 'Press & media',
        'other' => 'Something else',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'agency',
        'topic',
        'message',
        'page_url',
        'landing_page',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'gclid',
        'fbclid',
        'ip_address',
        'user_agent',
        'first_seen_at',
        'emailed_at',
    ];

    protected function casts(): array
    {
        return [
            'first_seen_at' => 'datetime',
            'emailed_at' => 'datetime',
        ];
    }

    public function topicLabel(): ?string
    {
        return $this->topic ? (self::TOPICS[$this->topic] ?? $this->topic) : null;
    }

    /**
     * The marketing channel this enquiry is credited to — derived, never
     * stored, so a better rule applies to old rows too.
     */
    public function channel(): string
    {
        return Attribution::channel($this->only(Attribution::COLUMNS));
    }

    /**
     * Free-text search across the things someone remembers about an enquiry.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $term).'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('agency', 'like', $like)
                ->orWhere('message', 'like', $like)
                ->orWhere('utm_source', 'like', $like)
                ->orWhere('utm_campaign', 'like', $like);
        });
    }
}
