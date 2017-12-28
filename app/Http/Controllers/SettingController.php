<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Util\Errors;
use App\Util\Utils;
use Response;
use Validator;

class SettingController extends Controller
{
    public function saveSettings(Request $request)
    {
        $this->validator($request);

        if (Auth::user()->isAdmin()) {
            $settingsFile = Utils::getSettingsFile();

            $settings = json_encode([ // TODO change
                'example' => $request['example'] ?? '',
            ]);

            file_put_contents($settingsFile, $settings);
        }

        $data = [];

        if (!empty($request['password'])) {
            $user = User::find(Auth::user()->id);
            $password = $request['password'];

            if ($user->update(['password' => bcrypt($password)])) {
                $data['password'] = true;
            }
        }

        return $data;
    }
    
    private function validator(Request $request)
    {
        $request->validate([
            'password' => 'nullable|string|min:6|confirmed',
        ]);
    }
}
