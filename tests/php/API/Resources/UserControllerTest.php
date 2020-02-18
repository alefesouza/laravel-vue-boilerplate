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

    public function setUp() : void
    {
        parent::setUp();

        $admin = factory(User::class)->create([
            'type_id' => User::TYPE_ADMIN,
        ]);

        $this->adminToken = \JWTAuth::fromUser($admin);

        $this->factory = factory(User::class)->make()->toArray();
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
        $factory['password'] = 'secret';
        $factory['password_confirmation'] = 'secret';

        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'POST',
                '/api/users',
                $factory
            )
            ->assertStatus(201)
            ->assertHeader('Content-Type', 'application/json');

        $json = json_decode($response->getContent());
        $factory['id'] = $json->id;

        unset($factory['password']);
        unset($factory['password_confirmation']);

        $response->assertJson($factory);

        $this->assertDatabaseHas('users', [
            'name' => $factory['name'],
            'email' => $factory['email'],
        ]);
    }

    public function testPUT()
    {
        $factory = $this->factory;
        $user = factory(User::class)->create();
        $factory['id'] = $user->id;

        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'PUT',
                '/api/users/'.$user->id,
                $factory
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson($factory);

        $this->assertDatabaseHas('users', [
            'name' => $factory['name'],
            'email' => $factory['email'],
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
            ->assertStatus(204);

        $this->assertSoftDeleted('users', [ 'name' => $user->name ]);
    }

    public function testDisallowAccessToNormalUser()
    {
        $user = factory(User::class)->create([
            'type_id' => User::TYPE_NORMAL,
        ]);

        $this->actingAs($user)
            ->json(
                'GET',
                '/api/users'
            )
            ->assertStatus(403)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('errors.forbidden'),
                'errors' => [
                    'message' => [ __('errors.forbidden') ],
                ],
            ]);
    }

    public function testDisallowAccessToGuestUser()
    {
        $this->json(
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
