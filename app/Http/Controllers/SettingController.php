<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Util\Errors;
use App\Util\Utils;

class SettingController extends Controller
{
    public function saveSettings(Request $request)
    {
        $request->validate([
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->user()->isAdmin()) {
            $settingsFile = Utils::getSettingsFile();

            $settings = json_encode([ // TODO change
                'example' => $request->input('example', ''),
            ]);

            file_put_contents($settingsFile, $settings);
        }

        $password = $request['password'];

        if (!empty($password)) {
            $request->user()->update([
                'password' => bcrypt($password),
            ]);
        }

        return [];
    }
}
