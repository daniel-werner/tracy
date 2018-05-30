<?php

namespace Tests\Feature;

use App\User;
use App\Workout;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testApiStore()
    {
        $user = factory(User::class)->create( [
            'password' => Hash::make( '123456' )
            ]
        );

        $data = [
            'type' => 1,
            'points' => [
                [
                    'segment_index' => 0,
                    'lat' => 46.126671,
                    'lng' => 19.642862,
                    'heart_rate' => 163,
                    'elevation' => 110,
                    'time' => '2018-04-22 09:23:10'
                ],
                [
                    'segment_index' => 0,
                    'lat' => 46.126671,
                    'lng' => 19.642862,
                    'heart_rate' => 164,
                    'elevation' => 111,
                    'time' => '2018-04-22 09:23:13'
                ]
            ]
        ];

        $headers = ['Authorization' => 'Basic ' . base64_encode($user->email.':'.'123456') ];
        $request = $this->post( '/api/workouts', $data, $headers );
        $request->assertStatus( 200 );

        $workouts = Workout::with('points')->get();

        $this->assertTrue( count($workouts) == 1 );
        $this->assertTrue( count($workouts[0]->points) == 2);
    }
}
