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
    public static function defaultTimeSlots(): array
    {
        return [
            [
                'name' => 'Breakfast',
                'start_time' => sprintf('%02d:00:00', 6),
                'end_time' => sprintf('%02d:00:00', 11),
            ],
            [
                'name' => 'Launch',
                'start_time' => sprintf('%02d:00:00', 11),
                'end_time' => sprintf('%02d:00:00', 13),
            ],
            [
                'name' => 'Dinner',
                'start_time' => sprintf('%02d:00:00', 13),
                'end_time' => sprintf('%02d:00:00', 6),
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
