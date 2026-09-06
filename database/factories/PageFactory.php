<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $slug = Str::slug($this->faker->unique()->words(3, true));

        return [
            'key' => $slug,
            'name' => ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'is_active' => true,
            'robots_index' => true,
            'robots_follow' => true,
            'og_type' => 'website',
            'twitter_card' => 'summary_large_image',
            'sitemap_priority' => '0.5',
            'sitemap_frequency' => 'weekly',
        ];
    }
}
