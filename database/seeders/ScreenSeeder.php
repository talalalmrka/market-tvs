<?php

namespace Database\Seeders;

use App\Models\Screen;
use App\Models\Slide;
use App\Models\TimeSlot;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class ScreenSeeder extends Seeder
{
    public static function defaultTimeSlots(): array
    {
        return [
            [
                'name' => 'Breakfast',
                'start_time' => '06:00',
                'end_time' => '11:00',
            ],
            [
                'name' => 'Launch',
                'start_time' => '11:00',
                'end_time' => '13:00',
            ],
            [
                'name' => 'Dinner',
                'start_time' => '13:00',
                'end_time' => '06:00',
            ],
        ];
    }
    public function run(): void
    {
        Screen::factory()
            ->count(5)
            ->user(1)
            ->create()
            ->each(function ($screen) {
                foreach (self::defaultTimeSlots() as $slotIndex => $slotData) {
                    $timeSlot = TimeSlot::factory()->create([
                        ...[
                            'screen_id' => $screen->id,
                        ],
                        ...$slotData,
                    ]);
                    $t = $slotIndex + 1;
                    for ($s = 1; $s <= 5; $s++) {

                        $slide = Slide::factory()->create([
                            'time_slot_id' => $timeSlot->id,
                            'order' => $s - 1,
                        ]);

                        $imagePath = public_path(
                            "assets/images/slides/t{$t}s{$s}.png"
                        );

                        $videoPath = public_path(
                            "assets/images/slides/video.mp4"
                        );
                        $filePath = $s == 3 ? $videoPath : $imagePath;

                        if (File::exists($filePath)) {
                            $slide
                                ->addMedia($filePath)
                                ->preservingOriginal()
                                ->toMediaCollection('file');
                        }
                    }
                }
            });
    }
}
