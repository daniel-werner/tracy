<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function getUserData(){
        $data = [
            'role_id' => User::ROLE_USER,
            'name' => 'Test User',
            'email' => 'email@emai.com',
            'timezone' => 'Europe/Budapest'
        ];

        return $data;
    }

    public function testCreateUserAsUser()
    {
        $user = User::factory()->create([
            'role_id' => User::ROLE_USER
        ]);
        $this->actingAs($user);

        $data = $this->getUserData();

        $data['password'] = '123123';
        $data['password_confirmation']  = '123123';

        $response = $this->post('/users', $data);
        $response->assertSessionMissing('errors');
        $response->assertStatus(403);

    }

    public function testEditUserAsUser()
    {
        $user = User::factory()->create([
            'role_id' => User::ROLE_USER
        ]);
        $this->actingAs($user);

        $data = $this->getUserData();
        $data['id'] = $user->id;

        $response = $this->put( '/users/' . $user->id, $data);

        $response->assertSessionMissing('errors');
        $response->assertStatus(403);

    }

    public function testCreateUser()
    {
        $user = User::factory()->create([
            'role_id' => User::ROLE_ADMIN
        ]);
        $this->actingAs($user);

        $data = $this->getUserData();

        $response = $this->post( '/users', $data);
        $response->assertSessionHasErrors('password');

        $data['password'] = '123123';
        $response = $this->post( '/users', $data);
        $response->assertSessionHasErrors('password');

        $data['password_confirmation']  = '123123';
        $response = $this->post( '/users', $data);
        $response->assertSessionMissing('errors');
        $response->assertStatus(302);

        unset($data['password']);
        unset($data['password_confirmation']);
        $this->assertDatabaseHas('users', $data);
    }

    public function testEditUser()
    {
        $user = User::factory()->create([
            'role_id' => User::ROLE_ADMIN
        ]);
        $this->actingAs($user);

        $data = $this->getUserData();
        $data['id'] = $user->id;

        $response = $this->put( '/users/' . $user->id, $data);

        $response->assertSessionMissing('errors');
        $response->assertStatus(302);

        $data['password'] = '123123';
        $response = $this->put( '/users/' . $user->id, $data);

        $response->assertSessionHasErrors('password');

        $data['password'] = '123123';
        $data['password_confirmation']  = '123123';
        $response = $this->put( '/users/' . $user->id, $data);
        $response->assertSessionMissing('errors');
        $response->assertStatus(302);

        unset($data['password']);
        unset($data['password_confirmation']);
        $this->assertDatabaseHas('users', $data);
    }

    public function testUserProfile()
    {
        $user = User::factory()->create([
            'role_id' => User::ROLE_USER
        ]);
        $this->actingAs($user);

        $data = $this->getUserData();
        $data['role_id'] = User::ROLE_ADMIN;

        $response = $this->put( '/users/profile', $data);
        $response->assertSessionHasErrors('role_id');

        $data['role_id'] = User::ROLE_USER;

        $response = $this->put( '/users/profile', $data);
        $response->assertSessionMissing('errors');
        $response->assertStatus(302);

        $data['password'] = '123123';
        $response = $this->put( '/users/profile', $data);

        $response->assertSessionHasErrors('password');

        $data['password'] = '123123';
        $data['password_confirmation']  = '123123';
        $response = $this->put( '/users/profile', $data);
        $response->assertSessionMissing('errors');
        $response->assertStatus(302);

        unset($data['password']);
        unset($data['password_confirmation']);
        $this->assertDatabaseHas('users', $data);
    }
}
