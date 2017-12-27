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
        $response = $this->actingAs($this->admin)->call(
                    'POST',
                    '/data/users',
                    [
                        'name' => '',
                        'email' => 'test@alefesouza.com',
                        'type_id' => 2,
                        'password' => 'aaaaaaa',
                    ]
                );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_name'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTWithInvalidEmail()
    {
        $response = $this->actingAs($this->admin)->call(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesou',
                'type_id' => 2,
                'password' => 'aaaaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_email'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesou']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTWithInvalidUserType()
    {
        $response = $this->actingAs($this->admin)->call(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 'dsfsdfsf',
                'password' => 'aaaaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_type'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTWithInvalidUserTypeWithAnInteger()
    {
        $response = $this->actingAs($this->admin)->call(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 3,
                'password' => 'aaaaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_type'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTWithInvalidPassword()
    {
        $response = $this->actingAs($this->admin)->call(
            'POST',
            '/data/users',
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 2,
                'password' => 'aaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_password'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPUTWithInvalidName()
    {
        $response = $this->actingAs($this->admin)->call(
                    'PUT',
                    '/data/users/'.$this->user->id,
                    [
                        'name' => '',
                        'email' => 'test@alefesouza.com',
                        'type_id' => 2,
                        'password' => 'aaaaaaa',
                    ]
                );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_name'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPUTWithInvalidEmail()
    {
        $response = $this->actingAs($this->admin)->call(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesou',
                'type_id' => 2,
                'password' => 'aaaaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_email'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesou']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPUTWithInvalidUserType()
    {
        $response = $this->actingAs($this->admin)->call(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 'dsfsdfsf',
                'password' => 'aaaaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_type'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPUTWithInvalidUserTypeWithAnInteger()
    {
        $response = $this->actingAs($this->admin)->call(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 4,
                'password' => 'aaaaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_type'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPUTWithInvalidPassword()
    {
        $response = $this->actingAs($this->admin)->call(
            'PUT',
            '/data/users/'.$this->user->id,
            [
                'name' => 'Alefe',
                'email' => 'test@alefesouza.com',
                'type_id' => 2,
                'password' => 'aaaaa'
            ]
        );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_password'));
        $this->assertDatabaseMissing('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}
