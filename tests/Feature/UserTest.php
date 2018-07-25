<?php

namespace Tests\Feature;

use App\User;
use App\Workout;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUserAsUser()
    {
        $user = factory(User::class)->create([
            'role_id' => User::ROLE_USER
        ]);
        $this->actingAs($user);

        $data = [
            'role_id' => User::ROLE_USER,
            'name' => 'Test User',
            'email' => 'email@emai.com'
        ];

        $data['password'] = '123123';
        $data['password_confirmation']  = '123123';

        $response = $this->post('/users', $data);
        $response->assertSessionMissing('errors');
        $response->assertStatus(403);

    }

    public function testEditUserAsUser()
    {
        $user = factory(User::class)->create([
            'role_id' => User::ROLE_USER
        ]);
        $this->actingAs($user);

        $data = [
            'id' => $user->id,
            'role_id' => User::ROLE_USER,
            'name' => 'Test User',
            'email' => 'email@emai.com'
        ];

        $response = $this->put( '/users/' . $user->id, $data);

        $response->assertSessionMissing('errors');
        $response->assertStatus(403);

    }

    public function testCreateUser()
    {
        $user = factory(User::class)->create([
            'role_id' => User::ROLE_ADMIN
        ]);
        $this->actingAs($user);

        $data = [
            'role_id' => User::ROLE_USER,
            'name' => 'Test User',
            'email' => 'email@emai.com'
        ];

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
        $user = factory(User::class)->create([
            'role_id' => User::ROLE_ADMIN
        ]);
        $this->actingAs($user);

        $data = [
            'id' => $user->id,
            'role_id' => User::ROLE_USER,
            'name' => 'Test User',
            'email' => 'email@emai.com'
        ];

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
        $user = factory(User::class)->create([
            'role_id' => User::ROLE_USER
        ]);
        $this->actingAs($user);

        $data = [
            'role_id' => User::ROLE_ADMIN,
            'name' => 'Test User',
            'email' => 'email@emai.com'
        ];

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
