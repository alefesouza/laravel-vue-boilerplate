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

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json');
    }

    public function testPOSTWithValidForm()
    {
        $response = $this->actingAs($this->admin)->json(
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
        
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'user' => 
                [
                    'id' => $json->user->id,
                    'name' => 'Alefe',
                    'email' => 'test@alefesouza.com',
                    'type_id' => 2,
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'test@alefesouza.com']);
    }

    public function testPUT()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin)->json(
                'PUT',
                '/data/users/'.$user->id,
                [
                    'name' => 'Alefe Souza',
                    'email' => 'contact@alefesouza.com',
                    'type_id' => 2,
                    'password' => 'aaaaaaaa',
                ]
            );
    
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'user' => 
                [
                    'id' => $user->id,
                    'name' => 'Alefe Souza',
                    'email' => 'contact@alefesouza.com',
                    'type_id' => 2,
                ],
            ]);

        $json = json_decode($response->getContent());
        
        $this->assertDatabaseHas('users', ['name' => 'Alefe Souza', 'email' => 'contact@alefesouza.com']);
    }

    public function testDELETE()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin)->call(
                'DELETE',
                '/data/users/'.$user->id
            );
        
        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([]);

        $json = json_decode($response->getContent());

        $this->assertDatabaseMissing('users', ['name' => 'Alefesssssssss']);
    }

    public function testDisallowAccessToNormalUser()
    {
        $user = factory(User::class)->create([
            'type_id' => '2',
        ]);

        $response = $this->actingAs($user)->json(
                'GET',
                '/data/users'
            );
        
        $response
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
