<?php

namespace Tests\Unit;

use App\User;
use App\Workout;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testCalculateParams()
    {
        $this->seed();

        $user = User::find(1);
        $this->actingAs($user);

        $workout = Workout::find(1);

        $expectedDistance = 28.89;
        $this->assertEquals($expectedDistance, $workout->distance);

        $expectedDuration = '01:37:04';
        $this->assertEquals($expectedDuration, $workout->duration);
    }

    /**
     * @param string $time
     * @param int $type
     * @param string $expectedTitle
     *
     * @test
     * @dataProvider provide_time_and_type_for_workout_title_creation
     */
    public function it_should_create_title_according_to_time(string $time, int $type, string $expectedTitle)
    {
        $time = Carbon::createFromTimeString($time);
        $title = Workout::createTitle($time, $type);
        $this->assertEquals($expectedTitle, $title);
    }

    public function provide_time_and_type_for_workout_title_creation(): array
    {
        return [
            ['2019-11-26 11:06:00', Workout::TYPE_RUNNING, 'Morning run'],
            ['2019-11-26 13:06:00', Workout::TYPE_RUNNING, 'Afternoon run'],
            ['2019-11-26 20:06:00', Workout::TYPE_RUNNING, 'Evening run'],
            ['2019-11-26 11:06:00', Workout::TYPE_CYCLING, 'Morning ride'],
            ['2019-11-26 13:06:00', Workout::TYPE_CYCLING, 'Afternoon ride'],
            ['2019-11-26 20:06:00', Workout::TYPE_CYCLING, 'Evening ride'],
        ];
    }
}
