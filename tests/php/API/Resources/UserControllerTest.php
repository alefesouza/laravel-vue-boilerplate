<?php

namespace Tests\API\Resources;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Util\Errors;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $factory;

    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'type_id' => 1,
        ]);

        $this->factory = factory(User::class)->make();
    }

    public function testGETAll()
    {
        $this->actingAs($this->admin)
            ->call(
                'GET',
                '/data/users'
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }

    public function testPOST()
    {
        $factory = $this->factory;

        $response = $this->actingAs($this->admin)
            ->json(
                'POST',
                '/data/users',
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

        $this->actingAs($this->admin)
            ->json(
                'PUT',
                '/data/users/'.$user->id,
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

        $this->actingAs($this->admin)
            ->call(
                'DELETE',
                '/data/users/'.$user->id
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([]);

        $this->assertDatabaseMissing('users', [ 'name' => $user->name ]);
    }

    public function testDisallowAccessToNormalUser()
    {
        $user = factory(User::class)->create([
            'type_id' => '2',
        ]);

        $this->actingAs($user)
            ->json(
                'GET',
                '/data/users'
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
