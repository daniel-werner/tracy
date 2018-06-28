<?php

namespace Tests\Feature;

use App\User;
use App\Workout;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkoutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $user = factory(User::class)->create();
        $workouts = factory(Workout::class,2)->create([
            'user_id' => $user->id,
            'type' => Workout::TYPE_RUNNING,
            'time' => '2018-07-28 14:01:01'
        ]);

        $workouts = $workouts->map(function ($workout) {
            return ['id' => $workout->id];
        });

        $workoutsBefore = factory(Workout::class,2)->create([
            'user_id' => $user->id,
            'type' => Workout::TYPE_CYCLING,
            'time' => '2018-07-26 14:01:01'
        ]);

        $workoutsBefore = $workoutsBefore->map(function ($workout) {
            return ['id' => $workout->id];
        });

        $workoutsAfter = factory(Workout::class,2)->create([
            'user_id' => $user->id,
            'type' => Workout::TYPE_CYCLING,
            'time' => '2018-07-30 14:01:01'
        ]);

        $workoutsAfter = $workoutsAfter->map(function ($workout) {
            return ['id' => $workout->id];
        });

        $request = $this
            ->actingAs($user)
            ->json( 'get', '/workouts/search', ['type' => Workout::TYPE_RUNNING]);

        $request->assertExactJson([
            'data' => $workouts
        ]);

        $request = $this
            ->actingAs($user)
            ->json( 'get', '/workouts/search', [ 'from' => '2018-07-27', 'to' => '2018-07-29']);

        $request->assertExactJson([
            'data' => $workouts
        ]);

        $request = $this
            ->actingAs($user)
            ->json( 'get', '/workouts/search', ['to' => '2018-07-27']);

        $request->assertExactJson([
            'data' => $workoutsBefore
        ]);

        $request = $this
            ->actingAs($user)
            ->json( 'get', '/workouts/search', ['from' => '2018-07-29']);

        $request->assertExactJson([
            'data' => $workoutsAfter
        ]);
    }

    public function testImportGpx()
    {
        $user = factory(User::class)->create([]);
        $this->actingAs($user);

        $response = $this->post( '/workouts', [
            'type' => Workout::TYPE_RUNNING,
            'workout_file' => new UploadedFile(base_path('tests/run.gpx'), 'run.gpx')
        ]);

        $response->assertStatus(302);

        $workout = Workout::with('points')->first();
        $this->assertTrue($workout->time === '2012-10-25 01:29:40');

        $this->assertTrue(count($workout->points) === 206);
    }
}
