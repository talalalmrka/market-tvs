<?php

namespace Database\Seeders;

use App\Models\Screen;
use App\Models\Slide;
use App\Models\TimeSlot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;



/* class ScreenSeeder extends Seeder
{

    public function run(): void
    {

        Screen::factory()
            ->count(5)
            ->user(1)
            ->has(
                TimeSlot::factory()
                    ->count(3)
                    ->has(Slide::factory()->count(5), 'slides'),
                'timeSlots'
            )
            ->create();
     }
}*/

class ScreenSeeder extends Seeder
{
    public function run(): void
    {
        Screen::factory()
            ->count(5)
            ->user(1)
            ->create()
            ->each(function ($screen) {

                // 3 TimeSlots
                for ($t = 1; $t <= 3; $t++) {

                    $timeSlot = TimeSlot::factory()->create([
                        'screen_id' => $screen->id,
                    ]);

                    // 5 Slides لكل TimeSlot
                    for ($s = 1; $s <= 5; $s++) {

                        $slide = Slide::factory()->create([
                            'time_slot_id' => $timeSlot->id,
                            'order' => $s - 1,
                        ]);

                        $imagePath = public_path(
                            "assets/images/t{$t}s{$s}.png"
                        );

                        if (File::exists($imagePath)) {
                            $slide
                                ->addMedia($imagePath)
                                ->preservingOriginal()
                                ->toMediaCollection('file');
                        }
                    }
                }
            });
    }
}
