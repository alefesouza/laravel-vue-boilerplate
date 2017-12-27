<?php

namespace Tests\API\Resources;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Util\Errors;
use App\Util\Utils;

class PageControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    public function setUp()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'type_id' => 1
        ]);
    }

    public function testPOSTSettingsWithValidForm()
    {
        $response = $this->actingAs($this->admin)->call(
                'POST',
                '/data/settings',
                [
                    'password' => 'aaaaaa',
                    'password_confirmation' => 'aaaaaa',
                ]
            );

        $json = json_decode($response->getContent());

        $this->assertFalse($json->error);

        $password = User::find($this->admin->id)->password;

        $this->assertTrue(Hash::check('aaaaaa', $password));
        $this->assertEquals($response->status(), 200);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGETVue()
    {
        $response = $this->actingAs($this->admin)
            ->call(
                'GET',
                '/data/vue'
            );

        $json = json_decode($response->getContent());

        $homeItems = [
            [
                'name' => trans_choice('strings.users', 2),
                'link' => 'users',
                'icon' => 'fa-users',
            ],
        ];

        $data = $json->data;
        
        $this->assertFalse($json->error);
        $this->assertTrue(empty($data->user->password));
        $this->assertEquals($this->admin->id, $data->user->id);
        $this->assertEquals($this->admin->name, $data->user->name);
        $this->assertEquals('/', $data->homePath);
        $this->assertEquals(route('logout'), $data->logoutUrl);
        $this->assertEquals(image('logo.png'), $data->logo);
        $this->assertEquals($homeItems[0]['link'], $data->homeItems[0]->link);
        $this->assertEquals($homeItems[0]['name'], $data->homeItems[0]->name);
        $this->assertEquals($homeItems[0]['icon'], $data->homeItems[0]->icon);

        $this->assertEquals($response->status(), 200);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTSettingsWithInvalidConfirmation()
    {
        $response = $this->actingAs($this->admin)->call(
                'POST',
                '/data/settings',
                [
                    'password' => 'aaaaaa',
                    'password_confirmation' => 'aaaaaab',
                ]
            );

        $json = json_decode($response->getContent());
        
        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_password_confirmation'));
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTSettingsWithInvalidCharNumber()
    {
        $response = $this->actingAs($this->admin)->call(
                    'POST',
                    '/data/settings',
                    [
                        'password' => 'aaaa',
                        'password_confirmation' => 'aaaa',
                    ]
                );

        $json = json_decode($response->getContent());

        $this->assertTrue($json->error);
        $this->assertEquals($json->description, __('errors.invalid_password'));
        $this->assertEquals($response->status(), 422);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPOSTSettingsWithoutPassword()
    {
        $response = $this->actingAs($this->admin)->call(
            'POST',
            '/data/settings'
        );

        $json = json_decode($response->getContent());
        
        $password = User::find($this->admin->id)->password;

        $this->assertTrue(Hash::check('secret', $password));

        $this->assertFalse($json->error);
        $this->assertFalse(isset($json->description));
        $this->assertEquals($response->status(), 200);
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}
