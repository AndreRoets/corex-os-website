<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageRedirect extends Model
{
    protected $fillable = [
        'old_slug',
        'page_id',
        'status_code',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
