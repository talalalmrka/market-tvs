<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SettingSeeder extends Seeder
{
    public static function defaultSettings(): array
    {
        $home = PostSeeder::createHome();
        $blog = PostSeeder::createBlog();

        return [
            // app
            [
                'type' => 'string',
                'key' => 'app.name',
                'value' => config('app.name'),
            ],
            [
                'type' => 'string',
                'key' => 'app.description',
                'value' => 'Markets & Restaurants Screens SlideShows',
            ],
            [
                'type' => 'string',
                'key' => 'app.url',
                'value' => config('app.url'),
            ],
            [
                'type' => 'string',
                'key' => 'app.admin_email',
                'value' => config('app.admin_email'),
            ],
            [
                'type' => 'string',
                'key' => 'app.env',
                'value' => config('app.env'),
            ],
            [
                'type' => 'boolean',
                'key' => 'app.debug',
                'value' => config('app.debug'),
            ],
            [
                'type' => 'boolean',
                'key' => 'app.eruda_enabled',
                'value' => config('app.eruda_enabled'),
            ],
            [
                'type' => 'file',
                'key' => 'app.logo',
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
                'key' => 'app.logo_light',
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
                'key' => 'app.logo_width',
                'value' => null,
            ],
            [
                'type' => 'string',
                'key' => 'app.logo_height',
                'value' => 35,
            ],
            [
                'type' => 'file',
                'key' => 'app.favicon',
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
                'key' => 'app.logo_label_enabled',
                'value' => false,
            ],
            [
                'type' => 'string',
                'key' => 'app.locale',
                'value' => config('app.locale'),
            ],
            [
                'type' => 'string',
                'key' => 'app.fallback_locale',
                'value' => config('app.fallback_locale'),
            ],
            [
                'type' => 'string',
                'key' => 'app.faker_locale',
                'value' => config('app.faker_locale'),
            ],
            [
                'type' => 'string',
                'key' => 'app.timezone',
                'value' => config('app.timezone'),
            ],
            [
                'type' => 'string',
                'key' => 'app.date_format',
                'value' => 'j F Y',
            ],
            [
                'type' => 'string',
                'key' => 'app.cipher',
                'value' => config('app.cipher'),
            ],
            [
                'type' => 'string',
                'key' => 'app.key',
                'value' => config('app.key'),
            ],
            [
                'type' => 'array',
                'key' => 'app.maintenance',
                'value' => config('app.maintenance'),
            ],

            // membership
            [
                'type' => 'boolean',
                'key' => 'membership.users_can_register',
                'value' => config('membership.users_can_register'),
            ],
            [
                'type' => 'array',
                'key' => 'membership.default_roles',
                'value' => config('membership.default_roles'),
            ],
            [
                'type' => 'boolean',
                'key' => 'membership.email_verification_required',
                'value' => config('membership.email_verification_required'),
            ],

            // ads
            [
                'type' => 'boolean',
                'key' => 'ads.auto_enabled',
                'value' => config('ads.auto_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'ads.auto_code',
                'value' => config('ads.auto_code'),
            ],
            [
                'type' => 'boolean',
                'key' => 'ads.above_content_enabled',
                'value' => config('ads.above_content_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'ads.above_content_code',
                'value' => config('ads.above_content_code'),
            ],
            [
                'type' => 'boolean',
                'key' => 'ads.below_content_enabled',
                'value' => config('ads.below_content_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'ads.below_content_code',
                'value' => config('ads.below_content_code'),
            ],

            // design
            [
                'type' => 'boolean',
                'key' => 'design.header_code_enabled',
                'value' => config('design.header_code_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'design.header_code',
                'value' => config('design.header_code'),
            ],
            [
                'type' => 'boolean',
                'key' => 'design.backtop_enabled',
                'value' => config('design.backtop_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'design.footer_copyrights',
                'value' => config('design.footer_copyrights'),
            ],
            [
                'type' => 'boolean',
                'key' => 'design.footer_code_enabled',
                'value' => config('design.footer_code_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'design.footer_code',
                'value' => config('design.footer_code'),
            ],
            [
                'type' => 'boolean',
                'key' => 'design.custom_css_enabled',
                'value' => config('design.custom_css_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'design.custom_css',
                'value' => config('design.custom_css'),
            ],
            [
                'type' => 'boolean',
                'key' => 'design.custom_js_enabled',
                'value' => config('design.custom_js_enabled'),
            ],
            [
                'type' => 'string',
                'key' => 'design.custom_js',
                'value' => config('design.custom_js'),
            ],
            [
                'type' => 'string',
                'key' => 'design.color_primary',
                'value' => config('design.color_primary'),
            ],
            [
                'type' => 'string',
                'key' => 'design.color_secondary',
                'value' => config('design.color_secondary'),
            ],
            [
                'type' => 'string',
                'key' => 'design.color_accent',
                'value' => config('design.color_accent'),
            ],

            // typography
            [
                'type' => 'string',
                'key' => 'typography.font_family',
                'value' => config('typography.font_family'),
            ],
            [
                'type' => 'string',
                'key' => 'typography.font_smoothing',
                'value' => config('typography.font_smoothing'),
            ],
            [
                'type' => 'string',
                'key' => 'typography.font_size',
                'value' => config('typography.font_size'),
            ],
            [
                'type' => 'string',
                'key' => 'typography.font_weight',
                'value' => config('typography.font_weight'),
            ],
            [
                'type' => 'string',
                'key' => 'typography.font_style',
                'value' => config('typography.font_style'),
            ],
            [
                'type' => 'string',
                'key' => 'typography.font_display',
                'value' => config('typography.font_display'),
            ],

            // reading
            [
                'type' => 'string',
                'key' => 'reading.front_type',
                'value' => $home ? 'page' : 'posts',
            ],
            [
                'type' => 'string',
                'key' => 'reading.front_page',
                'value' => $home?->id,
            ],
            [
                'type' => 'string',
                'key' => 'reading.posts_page',
                'value' => $blog?->id,
            ],
            [
                'type' => 'number',
                'key' => 'reading.posts_per_page',
                'value' => config('reading.posts_per_page'),
            ],
            [
                'type' => 'boolean',
                'key' => 'reading.disable_search_engines',
                'value' => config('reading.disable_search_engines'),
            ],

            // Mail
            [
                'type' => 'string',
                'key' => 'mail.default',
                'value' => config('mail.default'),
            ],
            [
                'type' => 'array',
                'key' => 'mail.from',
                'value' => config('mail.from'),
            ],
            [
                'type' => 'array',
                'key' => 'mail.mailers',
                'value' => config('mail.mailers'),
            ],
            ...collect(config('auth'))->map(fn($val, $key) => [
                'type' => 'array',
                'key' => "auth.{$key}",
                'value' => $val,
            ])->values()->toArray(),
            // Auth
            /* [
                'type' => 'array',
                'key' => 'auth.defaults',
                'value' => config('auth.defaults'),
            ],
            [
                'type' => 'array',
                'key' => 'auth.guards',
                'value' => config('auth.guards'),
            ], */
            // archive
            /*[
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
                'value' => 'Topics',
            ],
            [
                'type' => 'string',
                'key' => 'archive_category_seo_description',
                'value' => 'Browse the topics',
            ],
            [
                'type' => 'string',
                'key' => 'archive_category_permalink',
                'value' => 'topics',
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
                'value' => 'Tags',
            ],
            [
                'type' => 'string',
                'key' => 'archive_tag_seo_description',
                'value' => 'Browse the tags',
            ],
            [
                'type' => 'string',
                'key' => 'archive_tag_permalink',
                'value' => 'tags',
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
                'value' => 'Blog',
            ],
            [
                'type' => 'string',
                'key' => 'archive_post_seo_description',
                'value' => 'Quoteread blog',
            ],
            [
                'type' => 'string',
                'key' => 'archive_post_permalink',
                'value' => 'blog',
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
            ],*/
        ];

        // return config('settings');
    }

    /**
     * get all default settings
     * @return use Illuminate\Support\Collection<array>
     */
    public static function all()
    {
        return collect(self::defaultSettings());
    }

    /**
     * get all default settings
     * @return use Illuminate\Support\Collection<Setting>
     */
    public static function defaults()
    {
        return self::all()->map(function (array $item) {
            $type = data_get($item, 'type');
            $key = data_get($item, 'key');
            $value = $type === 'file' ? null : data_get($item, 'value', config($key));
            $setting = new Setting([
                'type' => $type,
                'key' => $key,
            ]);
            return $setting->setValue($value);
        });
    }

    /**
     * setting array with key
     * @param string $key
     * @return array|null
     */
    public static function withKey($key)
    {
        return self::all()->where('key', '=', $key)->first();
    }

    public static function getFiles($key) {}
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
    public function run()
    {
        $settings = self::defaults();
        $settings->each(function (Setting $setting) {
            $setting->save();
            if ($setting->type === 'file') {
                $files = self::getFiles($setting->key);
                if (!empty($files)) {
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            if (File::exists($file)) {
                                $setting->addMedia($file)
                                    ->preservingOriginal()
                                    ->toMediaCollection($setting->key);
                            } else {
                                $this->command->warn("File not exists: {$setting->key}: {$file}");
                            }
                        }
                    } elseif (is_string($files)) {
                        if (File::exists($files)) {
                            $setting->addMedia($files)
                                ->preservingOriginal()
                                ->toMediaCollection($setting->key);
                        } else {
                            $this->command->warn("File not exists: {$setting->key}: {$files}");
                        }
                    }
                }
            }
        });
    }
    public function runOld(): void
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
