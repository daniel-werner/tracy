<?php

namespace Tests\Unit;

use App\Point;
use App\User;
use App\Workout;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkoutCalculationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
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
}
