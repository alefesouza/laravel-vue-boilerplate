<?php

namespace Tests\API\Resources;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Util\Errors;
use App\Util\Utils;

class SettingControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;
    protected $adminToken;

    public function setUp() : void
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'type_id' => 1
        ]);

        $this->adminToken = \JWTAuth::fromUser($this->admin);
    }

    public function testPOSTSettingsWithValidForm()
    {
        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'POST',
                '/api/settings',
                [
                    'password' => 'aaaaaa',
                    'password_confirmation' => 'aaaaaa',
                ]
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([]);

        $password = User::find($this->admin->id)->password;

        $this->assertTrue(Hash::check('aaaaaa', $password));
    }

    public function testPOSTSettingsWithInvalidConfirmation()
    {
        $response = $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'POST',
                '/api/settings',
                [
                    'password' => 'aaaaaa',
                    'password_confirmation' => 'aaaaaab',
                ]
            )
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([
                'message' => __('validation.message'),
                'errors' => [
                    'password' => [ __('validation.confirmed', [
                        'attribute' => __('validation.attributes.password'),
                    ]) ],
                ],
            ]);

        $password = $this->admin->password;

        $this->assertFalse(Hash::check('aaaaaa', $password));
    }

    public function testPOSTSettingsWithInvalidCharNumber()
    {
        $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'POST',
                '/api/settings',
                [
                    'password' => 'aaaa',
                    'password_confirmation' => 'aaaa',
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

        $password = User::find($this->admin->id)->password;

        $this->assertFalse(Hash::check('aaaa', $password));
    }

    public function testPOSTSettingsWithoutPassword()
    {
        $this->withHeaders([
                'Authorization' => 'Bearer '.$this->adminToken,
            ])
            ->json(
                'POST',
                '/api/settings'
            )
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson([]);

        $password = User::find($this->admin->id)->password;

        $this->assertTrue(Hash::check('secret', $password));
    }
}
