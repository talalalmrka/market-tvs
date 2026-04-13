<?php

namespace Database\Seeders;

use App\Models\Screen;
use App\Models\Slide;
use App\Models\TimeSlot;
use App\Models\User;
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

    /**
     * create screens for user
     *
     * @param  int|string|User  $user
     * @param  int  $count  default 5
     * @return void
     */
    public function creatScreensForUser($user, $count = 5)
    {
        $user = user($user);
        if (! $user) {
            return;
        }
        Screen::factory()
            ->count($count)
            ->user($user->id)
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
                            'assets/images/slides/video.mp4'
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
        $this->command->info("Screens for user {$user->name} created.");
    }

    public function run(): void
    {
        $users = User::all();
        $users->each(fn (User $user) => $this->creatScreensForUser($user));
    }
}
