<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testRoles()
    {
        $user = User::factory()->create([
            'role_id' => User::ROLE_ADMIN
        ]);

        $this->actingAs($user);

        $roles = User::getRoles();

        $this->assertTrue( count($roles) === 2 );
        $this->assertArrayNotHasKey(User::ROLE_SUPERADMIN, $roles);

    }
}
