<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Util\Errors;
use App\Util\Utils;
use Response;

class SettingController extends Controller
{
    public function saveSettings(Request $request)
    {
        $password = $request['password'];

        $pass = !empty($password);

        $has_error = $this->validateForm($request->all(), $pass);

        if ($has_error !== false) {
            return Response::json([
                'error' => true,
                'description' => $has_error
            ], 422);
        }
        
        $data = ['error' => false];

        $user = User::find(Auth::user()->id);

        if (Auth::user()->isAdmin()) {
            $settingsFile = Utils::getSettingsFile();

            $settings = json_encode([ // TODO change
                'example' => $request['example'] ?? '',
            ]);

            file_put_contents($settingsFile, $settings);
        }

        if (empty($request['password'])) {
            unset($request['password']);
        } else {
            if ($user->update(['password' => bcrypt($password)])) {
                $data['password'] = true;
            }
        }

        return $data;
    }

    private function validateForm($data, $pass)
    {
        $validator = validator($data, [
            'password' => $pass ? 'required|min:6|confirmed' : '',
            'password_confirmation' => $pass ? 'required|min:6' : '',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            if (array_key_exists('password', $errors)) {
                if (strpos($errors['password'][0], 'confirmation') !== false) {
                    return __('errors.invalid_password_confirmation');
                }

                return __('errors.invalid_password');
            }
        }

        return false;
    }
}
