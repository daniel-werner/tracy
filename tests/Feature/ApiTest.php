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

    /** @var User $user */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
                'timezone' => 'Europe/Budapest',
                'password' => Hash::make('123456')
            ]
        );

        $this->artisan('passport:client', ['--password' => null, '--no-interaction' => true, '--personal' => true]);
        $this->artisan('passport:client', ['--password' => null, '--no-interaction' => true]);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testApiStore()
    {
        $requestData = [
            'data' =>
                [
                    [
                        'type' => 1,
                        'points' => [
                            [
                                'segment_index' => 0,
                                'lat' => 46.126671,
                                'lng' => 19.642862,
                                'heart_rate' => 163,
                                'elevation' => 110,
                                'time' => 1524503714000
                            ],
                            [
                                'segment_index' => 0,
                                'lat' => 46.126671,
                                'lng' => 19.642862,
                                'heart_rate' => 164,
                                'elevation' => 111,
                                'time' => 1524503714000
                            ]
                        ]
                    ]
                ]
        ];

        $headers = $this->createOauthHeaders();

        $request = $this->post('/api/workouts', $requestData, $headers);
        $request->assertStatus(200);

        $workouts = Workout::with('points')->get();

        $this->assertTrue(count($workouts) == 1);
        $this->assertTrue(count($workouts[0]->points) == 2);
        $this->assertTrue($workouts[0]->time == '2018-04-23 19:15:14');

        $data = $requestData['data'][0];

        unset($data['points'][0]['time']);
        unset($data['points'][0]['lat']);
        unset($data['points'][0]['lng']);

        $this->assertDatabaseHas('points', $data['points'][0]);
    }

    /**
     * @test
     */
    public function it_should_save_multiple_workouts()
    {
        $requestData = [
            'data' =>
                [
                    [
                        'type' => 1,
                        'points' => [
                            [
                                'segment_index' => 0,
                                'lat' => 46.126671,
                                'lng' => 19.642862,
                                'heart_rate' => 163,
                                'elevation' => 110,
                                'time' => 1524503714000
                            ],
                            [
                                'segment_index' => 0,
                                'lat' => 46.126671,
                                'lng' => 19.642862,
                                'heart_rate' => 164,
                                'elevation' => 111,
                                'time' => 1524503714000
                            ]
                        ]
                    ],
                    [
                        'type' => 2,
                        'points' => [
                            [
                                'segment_index' => 0,
                                'lat' => 46.126671,
                                'lng' => 19.642862,
                                'heart_rate' => 163,
                                'elevation' => 112,
                                'time' => 1524503714000
                            ],
                            [
                                'segment_index' => 0,
                                'lat' => 46.126671,
                                'lng' => 19.642862,
                                'heart_rate' => 164,
                                'elevation' => 113,
                                'time' => 1524503714000
                            ]
                        ]
                    ]
                ]
        ];

        $headers = $this->createOauthHeaders();

        $request = $this->post('/api/workouts', $requestData, $headers);
        $request->assertStatus(200);

        $workouts = Workout::with('points')->get();

        $this->assertTrue(count($workouts) == 2);
        $this->assertEquals(2, count($workouts[0]->points));
        $this->assertEquals($requestData['data'][0]['points'][0]['elevation'], $workouts[0]->points[0]->elevation);

        $this->assertEquals(2, count($workouts[1]->points));
        $this->assertEquals($requestData['data'][1]['points'][0]['elevation'], $workouts[1]->points[0]->elevation);
    }

    public function testLoginWithPersonalToken()
    {
        $headers = $this->createAuthHeaders();
        $response = $this->get('/api/workouts', $headers);
        $response->assertStatus(200);
    }

    public function testSimpleApiLogin()
    {
        $response = $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $accessToken = json_decode((string)$response->getContent());

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $request = $this->get('/api/workouts', $headers);
        $request->assertStatus(200);
    }

    public function testIncorrectTokenLogin()
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer invalid:auth:header'
        ];

        $request = $this->post('/api/workouts', [], $headers);
        $request->assertStatus(401);
    }

    /**
     * @return array
     */
    protected function createAuthHeaders(): array
    {
        $accessToken = $this->user->createToken('API token')->accessToken;

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];
        return $headers;
    }

    /**
     * @return array
     */
    protected function createOauthHeaders(): array
    {
        $client = \DB::table('oauth_clients')->where('password_client', 1)->first();

        $response = $this->post('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $this->user->email,
            'password' => '123456',
            'scope' => '*'
        ]);

        $response->assertStatus(200);
        $tokenData = json_decode((string)$response->getContent());
        $accessToken = $tokenData->access_token;

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];
        return $headers;
    }
}
