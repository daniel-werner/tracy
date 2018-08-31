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
            'timezone' => 'Europe/Budapest',
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

        $this->artisan('passport:client', ['--password' => null, '--no-interaction' => true] );

        $client = \DB::table('oauth_clients')->where('password_client', 1)->first();

        $response = $this->post('/oauth/token', [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $user->email,
                'password' => '123456',
                'scope' => '*'
        ]);

        $response->assertStatus( 200 );
        $tokenData = json_decode((string) $response->getContent());
        $accessToken = $tokenData->access_token;

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $accessToken,
        ];

        $request = $this->post( '/api/workouts', $data, $headers );
        $request->assertStatus( 200 );

        $workouts = Workout::with('points')->get();

        $this->assertTrue( count($workouts) == 1 );
        $this->assertTrue( count($workouts[0]->points) == 2);
        $this->assertTrue( $workouts[0]->time == '2018-04-22 09:23:10');

        unset($data['points'][0]['time']);
        unset($data['points'][0]['lat']);
        unset($data['points'][0]['lng']);

        $this->assertDatabaseHas('points', $data['points'][0]);
    }

    public function testLoginWithPersonalToken()
    {
        $user = factory(User::class)->create( [
                'timezone' => 'Europe/Budapest',
                'password' => Hash::make( '123456' )
            ]
        );

        $this->artisan('passport:client', ['--password' => null, '--no-interaction' => true, '--personal' => true] );

        $accessToken = $user->createToken('API token')->accessToken;

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $accessToken,
        ];

        $response = $this->get('/api/workouts', $headers);

        $response->assertStatus(200);
    }

    public function testSimpleApiLogin()
    {
        $user = factory(User::class)->create( [
                'timezone' => 'Europe/Budapest',
                'password' => Hash::make( '123456' )
            ]
        );

        $this->artisan('passport:client', ['--password' => null, '--no-interaction' => true, '--personal' => true] );

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response->assertStatus( 200 );

        $accessToken = json_decode((string) $response->getContent());

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. $accessToken,
        ];

        $request = $this->get( '/api/workouts', $headers );
        $request->assertStatus( 200 );
    }
}
