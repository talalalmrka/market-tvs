<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class SettingSeeder extends Seeder
{
    public static function defaultSettings(): array
    {
        return [
            [
                'type' => 'string',
                'key' => 'name',
                'value' => config('app.name'),
            ],
            [
                'type' => 'string',
                'key' => 'description',
                'value' => 'Markets & Restaurants Screens SlideShows',
            ],
            [
                'type' => 'string',
                'key' => 'url',
                'value' => config('app.url'),
            ],
            [
                'type' => 'string',
                'key' => 'admin_email',
                'value' => config('app.admin_email'),
            ],
            [
                'type' => 'file',
                'key' => 'logo',
                'value' => public_path('assets/images/logo.png'),
                'conversions' => [
                    'navbar' => [
                        'height' => '35',
                        'format' => 'webp',
                        'quality' => 80,
                        'responsive' => true,
                        'queued' => false,
                    ],
                ],
            ],
            [
                'type' => 'file',
                'key' => 'logo_light',
                'value' => public_path('assets/images/logo-light.png'),
                'conversions' => [
                    'navbar' => [
                        'height' => '35',
                        'format' => 'webp',
                        'quality' => 80,
                        'responsive' => true,
                        'queued' => false,
                    ],
                ],
            ],
            [
                'type' => 'string',
                'key' => 'logo_width',
                'value' => null,
            ],
            [
                'type' => 'string',
                'key' => 'logo_height',
                'value' => 35,
            ],
            [
                'type' => 'file',
                'key' => 'favicon',
                'value' => public_path('assets/images/favicon.png'),
                'conversions' => [
                    'favicon-16' => [
                        'width' => 16,
                        'height' => 16,
                        'format' => 'ico',
                    ],
                    'favicon-32' => [
                        'width' => 32,
                        'height' => 32,
                        'format' => 'ico',
                    ],
                    'favicon-48' => [
                        'width' => 48,
                        'height' => 48,
                        'format' => 'ico',
                    ],
                    'apple-touch-120' => [
                        'width' => 120,
                        'height' => 120,
                        'format' => 'ico',
                    ],
                    'apple-touch-167' => [
                        'width' => 167,
                        'height' => 167,
                        'format' => 'ico',
                    ],
                    'apple-touch-180' => [
                        'width' => 180,
                        'height' => 180,
                        'format' => 'ico',
                    ],
                    'android-192' => [
                        'width' => 192,
                        'height' => 192,
                        'format' => 'ico',
                    ],
                    'android-512' => [
                        'width' => 512,
                        'height' => 512,
                        'format' => 'ico',
                    ],
                ],
            ],
            [
                'type' => 'boolean',
                'key' => 'logo_label_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'locale',
                'value' => 'en',
            ],
            [
                'type' => 'string',
                'key' => 'timezone',
                'value' => 'UTC',
            ],
            [
                'type' => 'string',
                'key' => 'date_format',
                'value' => 'j F Y',
            ],
            [
                'type' => 'boolean',
                'key' => 'maintenance_mode_enabled',
                'value' => false,
            ],
            [
                'type' => 'boolean',
                'key' => 'site_closed',
                'value' => false,
            ],
            [
                'type' => 'boolean',
                'key' => 'users_can_register',
                'value' => true,
            ],
            [
                'type' => 'array',
                'key' => 'default_roles',
                'value' => [
                    'customer',
                ],
            ],
            [
                'type' => 'boolean',
                'key' => 'email_verification_required',
                'value' => false,
            ],
            [
                'type' => 'boolean',
                'key' => 'ads_auto_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'ads_auto_code',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'ads_above_content_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'ads_above_content_code',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'ads_below_content_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'ads_below_content_code',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'header_code_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'header_code',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'backtop_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'footer_copyrights',
                'value' => 'Copyrights reserved @ :link | :year',
            ],
            [
                'type' => 'boolean',
                'key' => 'footer_code_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'footer_code',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'custom_css_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'custom_css',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'custom_js_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'custom_js',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'eruda_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'color_primary',
                'value' => null,
            ],
            [
                'type' => 'string',
                'key' => 'color_secondary',
                'value' => null,
            ],
            [
                'type' => 'string',
                'key' => 'font_family',
                'value' => 'sans',
            ],
            [
                'type' => 'string',
                'key' => 'font_smoothing',
                'value' => 'antialiased',
            ],
            [
                'type' => 'string',
                'key' => 'font_size',
                'value' => null,
            ],
            [
                'type' => 'string',
                'key' => 'front_type',
                'value' => 'posts',
            ],
            [
                'type' => 'string',
                'key' => 'front_page',
                'value' => null,
            ],
            [
                'type' => 'string',
                'key' => 'posts_page',
                'value' => null,
            ],
            [
                'type' => 'number',
                'key' => 'posts_per_page',
                'value' => 8,
            ],
            [
                'type' => 'boolean',
                'key' => 'disable_search_engines',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'excerpt_enabled',
                'value' => true,
            ],
            [
                'type' => 'number',
                'key' => 'excerpt_length',
                'value' => 139,
            ],
            [
                'type' => 'string',
                'key' => 'excerpt_more',
                'value' => '...',
            ],
            [
                'type' => 'boolean',
                'key' => 'excerpt_preverse_words',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'share_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'share_label',
                'value' => __('Share:'),
            ],
            [
                'type' => 'array',
                'key' => 'share_buttons',
                'value' => [
                    [
                        'enabled' => true,
                        'name' => 'instagram',
                        'icon' => 'bi-instagram',
                        'url' => 'https://instagram.com/share/url={permalink}',
                    ],
                    [
                        'enabled' => true,
                        'name' => 'snapchat',
                        'icon' => 'bi-snapchat',
                        'url' => 'https://snapchat.com/share/url={permalink}',
                    ],
                    [
                        'enabled' => true,
                        'name' => 'telegram',
                        'icon' => 'bi-telegram',
                        'url' => 'https://t.me/share/url?url={permalink}',
                    ],
                    [
                        'enabled' => true,
                        'name' => 'pinterest',
                        'icon' => 'bi-pinterest',
                        'url' => 'https://pinterest.com/share/url?url={permalink}',
                    ],
                    [
                        'enabled' => true,
                        'name' => 'linkedin',
                        'icon' => 'bi-linkedin',
                        'url' => 'https://www.linkedin.com/shareArticle?url={permalink}',
                    ],
                    [
                        'enabled' => true,
                        'name' => 'whatsapp',
                        'icon' => 'bi-whatsapp',
                        'url' => 'https://wa.me/?text={name}\n{permalink}',
                    ],
                    [
                        'enabled' => true,
                        'name' => 'twitter',
                        'icon' => 'bi-twitter',
                        'url' => 'https://twitter.com/intent/tweet?url={permalink}',
                    ],
                    [
                        'enabled' => true,
                        'name' => 'facebook',
                        'icon' => 'bi-facebook',
                        'url' => 'https://www.facebook.com/sharer/sharer.php?u={permalink}',
                    ],
                ],
            ],
            // Single category
            [
                'type' => 'string',
                'key' => 'category_title',
                'value' => "{name}'s quotes and books",
            ],
            [
                'type' => 'string',
                'key' => 'category_seo_title',
                'value' => "{name}'s quotes and books",
            ],
            [
                'type' => 'string',
                'key' => 'category_seo_description',
                'value' => "Discover best {name}'s quotes and generate quote images and read & download books",
            ],

            [
                'type' => 'string',
                'key' => 'category_permalink',
                'value' => '{category:slug}',
            ],
            [
                'type' => 'boolean',
                'key' => 'category_description_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'category_description_label',
                'value' => null,
            ],
            [
                'type' => 'boolean',
                'key' => 'category_share_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'category_share_label',
                'value' => 'Share',
            ],
            [
                'type' => 'boolean',
                'key' => 'category_next_prev_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'category_next_label',
                'value' => 'Next',
            ],
            [
                'type' => 'string',
                'key' => 'category_prev_label',
                'value' => 'Previous',
            ],
            [
                'type' => 'boolean',
                'key' => 'related_categories_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'related_categories_label',
                'value' => 'Related topics',
            ],
            [
                'type' => 'number',
                'key' => 'related_categories_count',
                'value' => 5,
            ],

            // Single post
            [
                'type' => 'string',
                'key' => 'post_permalink',
                'value' => '{post:slug}',
            ],
            [
                'type' => 'boolean',
                'key' => 'post_meta_enabled',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'post_meta_author',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'post_meta_date',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'post_meta_categories',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'post_meta_views',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'post_meta_comments',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'post_tags_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'post_tags_label',
                'value' => __('Tags'),
            ],
            [
                'type' => 'boolean',
                'key' => 'post_share_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'post_share_label',
                'value' => __('Share ":name"'),
            ],
            [
                'type' => 'boolean',
                'key' => 'post_next_prev_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'post_next_label',
                'value' => __('Next'),
            ],
            [
                'type' => 'string',
                'key' => 'post_prev_label',
                'value' => __('Previous'),
            ],
            [
                'type' => 'boolean',
                'key' => 'related_posts_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'related_posts_label',
                'value' => __('Related posts'),
            ],
            [
                'type' => 'number',
                'key' => 'related_posts_count',
                'value' => 5,
            ],
            [
                'type' => 'string',
                'key' => 'related_posts_query',
                'value' => 'category',
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_enabled',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_login_required',
                'value' => false,
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_name_email_required',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_auto_close',
                'value' => false,
            ],
            [
                'type' => 'number',
                'key' => 'comments_auto_close_days',
                'value' => 7,
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_nested_enabled',
                'value' => true,
            ],
            [
                'type' => 'number',
                'key' => 'comments_nested_level',
                'value' => 5,
            ],
            [
                'type' => 'number',
                'key' => 'comments_per_page',
                'value' => 5,
            ],
            [
                'type' => 'string',
                'key' => 'comments_sort',
                'value' => 'newest',
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_approve_required',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_approve_previous',
                'value' => true,
            ],
            [
                'type' => 'number',
                'key' => 'comments_hold_links',
                'value' => 2,
            ],
            [
                'type' => 'boolean',
                'key' => 'comments_avatar_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'comments_hold_words',
                'value' => null,
            ],
            [
                'type' => 'string',
                'key' => 'comments_black_list',
                'value' => null,
            ],

            [
                'type' => 'boolean',
                'key' => 'cache_block_enabled',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'cache_block_classes_enabled',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'cache_block_styles_enabled',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'cache_block_atts_enabled',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'cache_page_blocks_enabled',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'cache_footer_blocks_enabled',
                'value' => true,
            ],

            // Archive category
            [
                'type' => 'string',
                'key' => 'archive_category_title',
                'value' => 'Topics',
            ],
            [
                'type' => 'string',
                'key' => 'archive_category_seo_title',
                'value' => "Topics",
            ],
            [
                'type' => 'string',
                'key' => 'archive_category_seo_description',
                'value' => "Browse the topics",
            ],
            [
                'type' => 'string',
                'key' => 'archive_category_permalink',
                'value' => "topics",
            ],
            [
                'type' => 'file',
                'key' => 'archive_category_image',
                'value' => public_path('assets/images/categories.jpeg'),
                'conversions' => [
                    'thumb' => [
                        'width' => 600,
                        'height' => 315,
                        'format' => 'jpg',
                    ],
                ],
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_date',
                'value' => false,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_views',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_quotes',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_books',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_posts',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_favorite',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_favorite_count',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_category_meta_share',
                'value' => true,
            ],

            // Archive tag
            [
                'type' => 'string',
                'key' => 'archive_tag_title',
                'value' => 'Tags',
            ],
            [
                'type' => 'string',
                'key' => 'archive_tag_seo_title',
                'value' => "Tags",
            ],
            [
                'type' => 'string',
                'key' => 'archive_tag_seo_description',
                'value' => "Browse the tags",
            ],
            [
                'type' => 'string',
                'key' => 'archive_tag_permalink',
                'value' => "tags",
            ],
            [
                'type' => 'file',
                'key' => 'archive_tag_image',
                'value' => public_path('assets/images/tags.jpeg'),
                'conversions' => [
                    'thumb' => [
                        'width' => 600,
                        'height' => 315,
                        'format' => 'jpg',
                    ],
                ],
            ],
            // Archive post
            [
                'type' => 'string',
                'key' => 'archive_post_title',
                'value' => 'Blog',
            ],
            [
                'type' => 'string',
                'key' => 'archive_post_seo_title',
                'value' => "Blog",
            ],
            [
                'type' => 'string',
                'key' => 'archive_post_seo_description',
                'value' => "Quoteread blog",
            ],
            [
                'type' => 'string',
                'key' => 'archive_post_permalink',
                'value' => "blog",
            ],
            [
                'type' => 'file',
                'key' => 'archive_post_image',
                'value' => public_path('assets/images/posts.jpeg'),
                'conversions' => [
                    'thumb' => [
                        'width' => 600,
                        'height' => 315,
                        'format' => 'jpg',
                    ],
                ],
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_post_meta_author',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_post_meta_date',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_post_meta_views',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_post_meta_category',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_post_meta_favorite',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_post_meta_favorite_count',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'archive_post_meta_share',
                'value' => true,
            ],

            // Sitemap
            [
                'type' => 'boolean',
                'key' => 'sitemap_generate_enabled',
                'value' => true,
            ],
            [
                'type' => 'string',
                'key' => 'sitemap_generate_time',
                'value' => '00:00',
            ],
            [
                'type' => 'number',
                'key' => 'sitemap_chunk_size',
                'value' => 1000,
            ],
            [
                'type' => 'boolean',
                'key' => 'sitemap_include_pages',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'sitemap_include_posts',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'sitemap_include_screens',
                'value' => true,
            ],
            [
                'type' => 'boolean',
                'key' => 'sitemap_include_categories',
                'value' => true,
            ],
            [
                'type' => 'array',
                'key' => 'footer_blocks',
                'value' => footer_blocks_default(),
            ],
        ];

        // return config('settings');
    }
    public static function all(): Collection
    {
        return collect(self::defaultSettings());
    }
    public static function withKey($key)
    {
        return self::all()->where('key', '=', $key)->first();
    }
    public static function getDefaultOption(string $key, $defaultValue = null)
    {
        $setting = arr_first(self::defaultSettings(), function ($data) use ($key) {
            return data_get($data, 'key') === $key;
        });
        return $setting ? resolve_option_value(data_get($setting, 'type'), data_get($setting, 'value')) : $defaultValue;
    }
    public static function getDefaultOptionType(string $key, $default = null)
    {
        $setting = arr_first(self::defaultSettings(), function ($data) use ($key) {
            return data_get($data, 'key') === $key;
        });
        return data_get($setting, 'type', $default);
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = self::defaultSettings();
        foreach ($defaultSettings as $item) {
            $type = data_get($item, 'type');
            $key = data_get($item, 'key');
            $originalValue = data_get($item, 'value');
            $value = match ($type) {
                'array' => json_encode($originalValue),
                'file' => null,
                default => $originalValue,
            };
            $setting = Setting::create([
                'type' => $type,
                'key' => $key,
                'value' => $value,
            ]);
            if ($setting && $type === 'file') {
                if (is_array($originalValue)) {
                    foreach ($originalValue as $file) {
                        $setting->addMedia($file)->preservingOriginal()->toMediaCollection($key);
                    }
                } elseif (is_string($originalValue)) {
                    $setting->addMedia($originalValue)->preservingOriginal()->toMediaCollection($key);
                }
            }
        }
    }
}
