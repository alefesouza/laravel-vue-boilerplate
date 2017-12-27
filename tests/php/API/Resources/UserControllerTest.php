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

    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'type_id' => 1,
        ]);
    }

    public function testGETAll()
    {
        $response = $this->actingAs($this->admin)
            ->call(
                'GET',
                '/data/users'
            );

        $this->assertEquals($response->status(), 200);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTWithValidForm()
    {
        $response = $this->actingAs($this->admin)->call(
                'POST',
                '/data/users',
                [
                    'name' => 'Alefe',
                    'email' => 'test@alefesouza.com',
                    'type_id' => 2,
                    'password' => 'aaaaaaaa',
                ]
            );
            
        $json = json_decode($response->getContent());

        $this->assertFalse($json->error);
        $this->assertDatabaseHas('users', ['email' => 'test@alefesouza.com']);
        $this->assertEquals($response->status(), 200);
        $this->assertEquals('Alefe', $json->user->name);
        $this->assertEquals('test@alefesouza.com', $json->user->email);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPUT()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin)->call(
                'PUT',
                '/data/users/'.$user->id,
                [
                    'name' => 'Alefesssssssss',
                    'email' => 'admin@admidsfsdfn.com',
                    'type_id' => 2,
                    'password' => 'aaaaaaaa',
                ]
            );

        $json = json_decode($response->getContent());
        
        $this->assertFalse($json->error);
        $this->assertDatabaseHas('users', ['name' => 'Alefesssssssss', 'email' => 'admin@admidsfsdfn.com']);
        $this->assertEquals('Alefesssssssss', $json->user->name);
        $this->assertEquals('admin@admidsfsdfn.com', $json->user->email);
        $this->assertEquals(2, $json->user->type_id);
        $this->assertEquals($response->status(), 200);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDELETE()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin)->call(
                'DELETE',
                '/data/users/'.$user->id
            );

        $json = json_decode($response->getContent());

        $this->assertFalse($json->error);
        $this->assertDatabaseMissing('users', ['name' => 'Alefesssssssss']);
        $this->assertEquals($response->status(), 200);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}
