<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Arr;

class PostSeeder extends Seeder
{
    public static function posts()
    {
        return [
            [
                'name' => 'Home',
                'type' => 'page',
                'content' => 'This is home page',
            ],
            [
                'name' => 'Blog',
                'type' => 'page',
                'content' => '',
            ],
            [
                'name' => 'About Us',
                'type' => 'page',
                'content' => "<p>Welcome to our platform. We are dedicated to providing the best service to our customers. Learn more about our values, mission, and the story behind our store.</p>",
            ],
            [
                'name' => 'Contact Us',
                'type' => 'page',
                'content' => '<p>You can contact us on email <a href="mailto:contact@example.com">contact@example.com</a>.</p>',
            ],
            [
                'name' => 'Privacy policy',
                'type' => 'page',
                'content' => "<p>Your privacy is important to us at our platform. We are committed to protecting your personal data and respecting your privacy.</p>",
            ],
            [
                'name' => 'Fadgram laravel starter kit',
                'thumbnail' => public_path('assets/images/laravel-fadgram.png'),
                'type' => 'post',
            ],
            [
                'name' => 'Fadgram UI',
                'thumbnail' => public_path('assets/images/fadgram-ui.png'),
                'type' => 'post',
            ],
            [
                'name' => 'Default layout',
                'type' => 'post',
                'meta' => [
                    'template' => 'default',
                ],
            ],
            [
                'name' => 'Cover layout',
                'type' => 'post',
                'meta' => [
                    'template' => 'cover',
                ],
            ],
            [
                'name' => 'Curve layout',
                'type' => 'post',
                'meta' => [
                    'template' => 'curve',
                ],
            ],
        ];
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::posts() as $item) {
            $data = Arr::except($item, ['thumbnail', 'images', 'meta']);
            Post::factory(1, [
                ...$data,
                ...[
                    'user_id' => 1,
                    'type' => 'post',
                    'status' => 'publish',
                ],
            ])->create()
                ->each(function (Post $post) use ($item) {
                    // thumbnail
                    $thumbnail = data_get($item, 'thumbnail');
                    if ($thumbnail) {
                        $post->addMedia($thumbnail)
                            ->preservingOriginal()
                            ->toMediaCollection($post->thumbnailCollection());
                    }
                    //meta
                    $meta = data_get($item, 'meta');
                    if (is_array($meta)) {
                        $post->saveMetas($meta);
                    }
                });
        }

        Post::factory(25)->create();
    }
}
