<?php

namespace Tests\API\Resources;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Util\Errors;
use App\User;

class UserControllerValidationTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $factory;
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'type_id' => 1,
        ]);

        $this->factory = factory(User::class)->make();
        $this->user = factory(User::class)->create();
    }

    public function testPOSTWithInvalidName()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'POST',
                '/data/users',
                [
                    'name' => '',
                    'email' => $factory->email,
                    'type_id' => 2,
                    'password' => $factory->password,
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'name' => [ __('validation.required', [
                        'attribute' => 'name',
                    ]) ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }

    public function testPOSTWithInvalidEmail()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'POST',
                '/data/users',
                [
                    'name' => $factory->name,
                    'email' => 'test@example',
                    'type_id' => 2,
                    'password' => $factory->password
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'email' => [ __('validation.email', [
                        'attribute' => __('validation.attributes.email'),
                    ]) ],
                ],
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'test@example']);
    }

    public function testPOSTWithInvalidUserType()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'POST',
                '/data/users',
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 'dsfsdfsf',
                    'password' => $factory->password
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }

    public function testPOSTWithInvalidUserTypeWithAnInteger()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'POST',
                '/data/users',
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 3,
                    'password' => $factory->password
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }

    public function testPOSTWithInvalidPassword()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'POST',
                '/data/users',
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 2,
                    'password' => 'aaaaa'
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'password' => [ __('validation.min.string', [
                        'attribute' => __('validation.attributes.password'),
                        'min' => 6,
                    ]) ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }

    public function testPUTWithInvalidName()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'PUT',
                '/data/users/'.$this->user->id,
                [
                    'name' => '',
                    'email' => $factory->email,
                    'type_id' => 2,
                    'password' => $factory->password,
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'name' => [ __('validation.required', [
                        'attribute' => 'name',
                    ]) ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }

    public function testPUTWithInvalidEmail()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'PUT',
                '/data/users/'.$this->user->id,
                [
                    'name' => $factory->name,
                    'email' => 'test@example',
                    'type_id' => 2,
                    'password' => $factory->password,
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'email' => [ __('validation.email', [
                        'attribute' => __('validation.attributes.email'),
                    ]) ],
                ],
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'test@example']);
    }

    public function testPUTWithInvalidUserType()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'PUT',
                '/data/users/'.$this->user->id,
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 'dsfsdfsf',
                    'password' => $factory->password,
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }

    public function testPUTWithInvalidUserTypeWithAnInteger()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'PUT',
                '/data/users/'.$this->user->id,
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 4,
                    'password' => $factory->password,
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }

    public function testPUTWithInvalidPassword()
    {
        $factory = $this->factory;

        $this->actingAs($this->admin)
            ->json(
                'PUT',
                '/data/users/'.$this->user->id,
                [
                    'name' => $factory->name,
                    'email' => $factory->email,
                    'type_id' => 2,
                    'password' => 'aaaaa'
                ]
            )
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'password' => [ __('validation.min.string', [
                        'attribute' => __('validation.attributes.password'),
                        'min' => 6,
                    ]) ],
                ],
            ]);

        $this->assertDatabaseMissing('users', [ 'email' => $factory->email ]);
    }
}
