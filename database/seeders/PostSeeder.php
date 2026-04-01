<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PostSeeder extends Seeder
{
    public static function posts()
    {
        return [
            /*[
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
            ],*/
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

    public static function createHome(): Post
    {
        $admin = UserSeeder::createAdmin();
        $home = Post::firstOrCreate(
            [
                'type' => 'page',
                'slug' => 'home', // شرط البحث
            ],
            [
                'user_id' => $admin->id,
                'name' => 'Home',
                'content' => '<p>Welcome to our Homepage</p>',
                'status' => 'publish',
            ]
        );

        return $home;
    }

    public static function createBlog(): Post
    {
        $admin = UserSeeder::createAdmin();
        $blog = Post::firstOrCreate(
            [
                'type' => 'page',
                'slug' => 'blog', // شرط البحث
            ],
            [
                'user_id' => $admin->id,
                'name' => 'Blog',
                'content' => '',
                'status' => 'publish',
            ]
        );

        return $blog;
    }

    public static function createAbout(): Post
    {
        $admin = UserSeeder::createAdmin();
        $about = Post::firstOrCreate(
            [
                'type' => 'page',
                'slug' => 'about-us',
            ],
            [
                'user_id' => $admin->id,
                'name' => 'About us',
                'content' => '<p>Welcome to our platform. We are dedicated to providing the best service to our customers. Learn more about our values, mission, and the story behind our store.</p>',
                'status' => 'publish',
            ]
        );

        return $about;
    }

    public static function createContact(): Post
    {
        $admin = UserSeeder::createAdmin();
        $contact = Post::firstOrCreate(
            [
                'type' => 'page',
                'slug' => 'contact-us',
            ],
            [
                'user_id' => $admin->id,
                'name' => 'Contact us',
                'content' => '<p>You can contact us on email <a href="mailto:contact@example.com">contact@example.com</a>.</p>',
                'status' => 'publish',
            ]
        );

        return $contact;
    }

    public static function createPrivacy(): Post
    {
        $admin = UserSeeder::createAdmin();
        $privacy = Post::firstOrCreate(
            [
                'type' => 'page',
                'slug' => 'privacy-policy',
            ],
            [
                'user_id' => $admin->id,
                'name' => 'Privacy policy',
                'content' => '<p>Your privacy is important to us at our platform. We are committed to protecting your personal data and respecting your privacy.</p>',
                'status' => 'publish',
            ]
        );

        return $privacy;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = UserSeeder::createAdmin();
        self::createHome();
        self::createBlog();
        self::createAbout();
        self::createContact();
        self::createPrivacy();
        foreach (self::posts() as $item) {
            $data = Arr::except($item, ['thumbnail', 'images', 'meta']);
            Post::factory(1, [
                ...$data,
                ...[
                    'user_id' => $admin->id,
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
                    // meta
                    $meta = data_get($item, 'meta');
                    if (is_array($meta)) {
                        $post->saveMetas($meta);
                    }
                });
        }

        Post::factory(25)->create();
    }
}
