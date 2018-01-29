<?php

namespace Tests\API\Resources;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Util\Errors;
use App\Util\Utils;

class HomeControllerTest extends TestCase
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
            [
                'name' => __('strings.example'),
                'icon' => 'fa-lightbulb-o',
                'link' => 'example',
            ],
        ];

        $settingsFile = Utils::getSettingsFile();

        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true);
        } else {
            $settings = [];
        }

        $response
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'appName' => config('app.name', 'Laravel'),
                'homePath' => '/',
                'logo' => image('logo.png'),
                'logoutUrl' => route('logout'),
                'user' => [
                    'name' => $this->admin->name,
                    'email' => $this->admin->email,
                    'type_id' => $this->admin->type_id,
                    'id' => $this->admin->id,
                ],
                'homeItems' => $homeItems,
                'settings' => $settings,
            ]);
    }
}
