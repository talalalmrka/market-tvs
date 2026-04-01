<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public Post $home;

    public Post $blog;

    public Post $about;

    public Post $contact;

    public Post $privacy;

    public function createHeaderMenu()
    {
        $headerMenu = Menu::create([
            'name' => 'Header menu',
            'position' => 'header',
            'class_name' => 'header-menu',
        ]);
        if ($headerMenu) {
            // Home
            if ($this->home) {
                $headerMenu->items()->create([
                    'name' => 'Home',
                    'icon' => 'bi-house-fill',
                    'model_type' => $this->home->getMorphClass(),
                    'model_id' => $this->home->id,
                ]);
            }

            // Blog
            if ($this->blog) {
                $headerMenu->items()->create([
                    'name' => 'Blog',
                    'icon' => 'bi-newspaper',
                    'model_type' => $this->blog->getMorphClass(),
                    'model_id' => $this->blog->id,
                ]);
            }

            // Screens
            $headerMenu->items()->create([
                'name' => 'Screens',
                'icon' => 'bi-screen',
                'url' => url('/screens'),
            ]);

            // Topics
            $topicsItem = $headerMenu->items()->create([
                'name' => 'Topics',
                'icon' => 'bi-folder',
                'url' => url('#'),
            ]);

            if ($topicsItem) {
                $categories = Category::type('category')->get();
                foreach ($categories as $category) {
                    $headerMenu->items()->create([
                        'parent_id' => $topicsItem->id,
                        'name' => $category->name,
                        'model_type' => $category->getMorphClass(),
                        'model_id' => $category->id,
                    ]);
                }
            }

            // Tags
            $tagsItem = $headerMenu->items()->create([
                'name' => 'Tags',
                'icon' => 'bi-tag',
                'url' => url('#'),

            ]);

            if ($tagsItem) {
                $tags = Category::type('tag')->get();
                foreach ($tags as $tag) {
                    $headerMenu->items()->create([
                        'parent_id' => $tagsItem->id,
                        'name' => $tag->name,
                        'model_type' => $tag->getMorphClass(),
                        'model_id' => $tag->id,
                    ]);
                }
            }

            // Nested
            $nestedItem = $headerMenu->items()->create([
                'name' => 'Nested',
                'icon' => 'bi-list',
                'url' => url('#'),

            ]);
            if ($nestedItem) {
                for ($i = 1; $i < 4; $i++) {
                    $headerMenu->items()->create([
                        'parent_id' => $nestedItem->id,
                        'name' => "Sub {$i}",
                        'icon' => 'bi-folder',
                        'url' => url("/sub-{$i}"),

                    ]);
                }
            }
        }
    }

    public function createFooter1Menu()
    {
        // footer 1
        $footer1 = Menu::create([
            'name' => 'Footer 1 menu',
            'position' => 'footer1',
            'class_name' => 'footer1-menu',
        ]);
        if ($footer1) {

            // Home
            if ($this->home) {
                $footer1->items()->create([
                    'name' => 'Home',
                    'icon' => 'bi-house-fill',
                    'model_type' => $this->home->getMorphClass(),
                    'model_id' => $this->home->id,
                ]);
            }
            // Blog
            if ($this->blog) {
                $footer1->items()->create([
                    'name' => 'Blog',
                    'icon' => 'bi-newspaper',
                    'model_type' => $this->blog->getMorphClass(),
                    'model_id' => $this->blog->id,
                ]);
            }

            $footer1->items()->create([
                'name' => 'Screens',
                'icon' => 'bi-screen',
                'url' => url('/screens'),
            ]);
        }
    }

    public function createFooter2Menu()
    {
        // footer2
        $footer2 = Menu::create([
            'name' => 'Footer 2 Menu',
            'position' => 'footer2',
            'class_name' => 'footer2-menu',
        ]);
        if ($footer2) {
            $about = Post::withSlug('about-us');
            if ($about instanceof Post) {
                $footer2->items()->create([
                    'name' => $about->name,
                    'icon' => 'bi-info-circle',
                    'model_type' => $about->getMorphClass(),
                    'model_id' => $about->id,
                ]);
            }

            $contact = Post::withSlug('contact-us');
            if ($contact instanceof Post) {
                $footer2->items()->create([
                    'name' => $contact->name,
                    'icon' => 'bi-telephone-fill',
                    'model_type' => $contact->getMorphClass(),
                    'model_id' => $contact->id,
                ]);
            }

            $privacy = Post::withSlug('privacy-policy');
            if ($privacy instanceof Post) {
                $footer2->items()->create([
                    'name' => $privacy->name,
                    'icon' => 'bi-hammer',
                    'model_type' => $privacy->getMorphClass(),
                    'model_id' => $privacy->id,
                ]);
            }
        }
    }

    public function createSocialMenu()
    {
        // social
        $socialMenu = Menu::create([
            'name' => 'Social menu',
            'position' => 'social',
            'class_name' => 'social-menu',
        ]);
        if ($socialMenu) {
            $socialMenu->items()->create([
                'icon' => 'bi-facebook',
                'url' => 'https://facebook.com/fadgram',
            ]);
            $socialMenu->items()->create([
                'icon' => 'bi-twitter',
                'url' => 'https://x.com/fadgram',
            ]);
            $socialMenu->items()->create([
                'icon' => 'bi-telegram',
                'url' => 'https://t.me/fadgram',
            ]);
            $socialMenu->items()->create([
                'icon' => 'bi-instagram',
                'url' => 'https://www.instagram.com/fadgram/',
            ]);
        }
    }

    public function createTestMenu()
    {
        $testMenu = Menu::create([
            'name' => 'Test Menu',
            'position' => null,
            'class_name' => 'test-menu',
        ]);
        if ($testMenu) {
            // Home
            if ($this->home) {
                $testMenu->items()->create([
                    'name' => 'Home',
                    'icon' => 'bi-house-fill',
                    'model_type' => $this->home->getMorphClass(),
                    'model_id' => $this->home->id,
                ]);
            }

            // Blog
            if ($this->blog) {
                $testMenu->items()->create([
                    'name' => 'Blog',
                    'icon' => 'bi-newspaper',
                    'model_type' => $this->blog->getMorphClass(),
                    'model_id' => $this->blog->id,
                ]);
            }

            // Screens
            $testMenu->items()->create([
                'name' => 'Screens',
                'icon' => 'bi-screen',
                'url' => url('/screens'),
            ]);

            // Topics
            $topicsItem = $testMenu->items()->create([
                'name' => 'Topics',
                'icon' => 'bi-folder',
                'url' => url('#'),
            ]);

            if ($topicsItem) {
                $categories = Category::type('category')->limit(3)->get();
                foreach ($categories as $category) {
                    $testMenu->items()->create([
                        'parent_id' => $topicsItem->id,
                        'name' => $category->name,
                        'model_type' => $category->getMorphClass(),
                        'model_id' => $category->id,
                    ]);
                }
            }

            // Tags
            $tagsItem = $testMenu->items()->create([
                'name' => 'Tags',
                'icon' => 'bi-tag',
                'url' => url('#'),
            ]);

            if ($tagsItem) {
                $tags = Category::type('tag')->limit(3)->get();
                foreach ($tags as $tag) {
                    $testMenu->items()->create([
                        'parent_id' => $tagsItem->id,
                        'name' => $tag->name,
                        'model_type' => $tag->getMorphClass(),
                        'model_id' => $tag->id,
                    ]);
                }
            }

            // Nested
            $nestedItem = $testMenu->items()->create([
                'name' => 'Nested',
                'icon' => 'bi-list',
                'url' => url('#'),

            ]);
            if ($nestedItem) {
                for ($i = 1; $i < 4; $i++) {
                    $testMenu->items()->create([
                        'parent_id' => $nestedItem->id,
                        'name' => "Sub {$i}",
                        'icon' => 'bi-folder',
                        'url' => url("/sub-{$i}"),

                    ]);
                }
            }
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->home = PostSeeder::createHome();
        $this->blog = PostSeeder::createBlog();
        $this->about = PostSeeder::createAbout();
        $this->contact = PostSeeder::createContact();
        $this->privacy = PostSeeder::createPrivacy();
        $this->createHeaderMenu();
        $this->createFooter1Menu();
        $this->createFooter2Menu();
        $this->createSocialMenu();
        $this->createTestMenu();
    }
}
