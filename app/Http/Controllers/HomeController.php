<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Util\Utils;
use Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app');
    }

    /**
     * Get initial data for Vue.js application
     */
    public function vue()
    {
        $homeItems = [
            [
                'name' => 'strings.users',
                'icon' => 'users',
                'link' => 'users',
            ],
            [
                'name' => 'strings.users_graphql',
                'icon' => 'users',
                'link' => 'users/graphql',
            ],
            [
                'name' => 'strings.messages',
                'icon' => 'envelope',
                'link' => 'messages',
            ],
            [
                'name' => 'strings.example',
                'icon' => 'lightbulb',
                'link' => 'example',
            ],
        ];

        $settingsFile = Utils::getSettingsFile();

        $settings = array();

        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile));
        }

        $data = [
            'homeItems' => $homeItems,
            'settings' => $settings,
        ];

        return $data;
    }
}
