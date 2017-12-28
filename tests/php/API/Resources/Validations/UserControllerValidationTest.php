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
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'type_id' => 1,
        ]);

        $this->user = factory(User::class)->create();
    }

    public function testPOSTWithInvalidName()
    {
        $response = $this->actingAs($this->admin)->json(
                    'POST',
                    '/data/users',
                    [
                        'name' => '',
                        'email' => 'test@alefesouza.com',
                        'type_id' => 2,
                        'password' => 'aaaaaaa',
                    ]
                );

        $response
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

        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPOSTWithInvalidEmail()
    {
        $response = $this->actingAs($this->admin)->json(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesou',
                'type_id' => 2,
                'password' => 'aaaaaaa'
            ]
        );

        $response
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

        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesou']);
    }

    public function testPOSTWithInvalidUserType()
    {
        $response = $this->actingAs($this->admin)->json(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 'dsfsdfsf',
                'password' => 'aaaaaaa'
            ]
        );

        $response
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);
    
        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPOSTWithInvalidUserTypeWithAnInteger()
    {
        $response = $this->actingAs($this->admin)->json(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 3,
                'password' => 'aaaaaaa'
            ]
        );

        $response
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);
    
        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPOSTWithInvalidPassword()
    {
        $response = $this->actingAs($this->admin)->json(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 2,
                'password' => 'aaaaa'
            ]
        );

        $response
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

        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPUTWithInvalidName()
    {
        $response = $this->actingAs($this->admin)->json(
                    'PUT',
                    '/data/users/'.$this->user->id,
                    [
                        'name' => '',
                        'email' => 'test@alefesouza.com',
                        'type_id' => 2,
                        'password' => 'aaaaaaa',
                    ]
                );

        $response
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

        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPUTWithInvalidEmail()
    {
        $response = $this->actingAs($this->admin)->json(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesou',
                'type_id' => 2,
                'password' => 'aaaaaaa'
            ]
        );

        $response
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

        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesou']);
    }

    public function testPUTWithInvalidUserType()
    {
        $response = $this->actingAs($this->admin)->json(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 'dsfsdfsf',
                'password' => 'aaaaaaa'
            ]
        );
    

        $response
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);
    
        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPUTWithInvalidUserTypeWithAnInteger()
    {
        $response = $this->actingAs($this->admin)->json(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 4,
                'password' => 'aaaaaaa'
            ]
        );
    
        $response
            ->assertStatus(422)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'type_id' => [ __('users.invalid_user_type') ],
                ],
            ]);
    
        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPUTWithInvalidPassword()
    {
        $response = $this->actingAs($this->admin)->json(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 2,
                'password' => 'aaaaa'
            ]
        );

        $response
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

        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
    }
}
