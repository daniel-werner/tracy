<?php

namespace Tests\Feature;

use App\User;
use App\Workout;
use Illuminate\Support\Facades\Hash;
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
    public function testWorkoutSearch()
    {
        $user = factory(User::class)->create();
        $workouts = factory(Workout::class,2)->create([
            'user_id' => $user->id,
            'type' => Workout::TYPE_RUNNING
        ]);

        $workoutIds = [];

        foreach( $workouts as $workout ){
            $workoutIds[] = array(
                'id' => $workout->id
            );
        }

        $workouts = $workouts->merge(factory(Workout::class,2)->create([
            'user_id' => $user->id,
            'type' => Workout::TYPE_CYCLING
        ]));

        $request = $this
            ->actingAs($user)
            ->json( 'get', '/workouts/search', ['type' => Workout::TYPE_RUNNING]);

        $request->assertJson([
            'data' => $workoutIds
        ]);

    }
}
