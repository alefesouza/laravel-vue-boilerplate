<?php

namespace Tests\API\Resources;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Util\Errors;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminToken;
    protected $factory;

    public function setUp()
    {
        parent::setUp();

        $admin = factory(User::class)->create([
            'type_id' => 1
        ]);

        $this->adminToken = \JWTAuth::fromUser($admin);

        $this->factory = factory(User::class)->make();
    }

    public function testGETAll()
    {
        $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'GET',
                '/api/users'
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }

    public function testPOST()
    {
        $factory = $this->factory;

        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'POST',
                '/api/users',
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 2,
                    'password' => $factory->password,
                    'password_confirmation' => $factory->password,
                ]
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');

        $json = json_decode($response->getContent());

        $response
            ->assertJson([
                'id' => $json->id,
                'name' => $factory->name,
                'email' => $factory->email,
                'type_id' => 2,
            ]);

        $this->assertDatabaseHas('users', [ 'email' => $factory->email ]);
    }

    public function testPUT()
    {
        $factory = $this->factory;
        $user = factory(User::class)->create();

        $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'PUT',
                '/api/users/'.$user->id,
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 2,
                    'password' => $factory->password,
                    'password_confirmation' => $factory->password,
                ]
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'id' => $user->id,
                'name' => $factory->name,
                'email' => $factory->email,
                'type_id' => 2,
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $factory->name,
            'email' => $factory->email,
        ]);
    }

    public function testDELETE()
    {
        $user = factory(User::class)->create();

        $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'DELETE',
                '/api/users/'.$user->id
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([]);

        $this->assertSoftDeleted('users', [ 'name' => $user->name ]);
    }

    public function testDisallowAccessToNormalUser()
    {
        $user = factory(User::class)->create([
            'type_id' => '2',
        ]);

        $this->actingAs($user)
            ->json(
                'GET',
                '/api/users'
            )
            ->assertStatus(401)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('errors.unauthorized'),
                'errors' => [
                    'message' => [ __('errors.unauthorized') ],
                ],
            ]);
    }
}
