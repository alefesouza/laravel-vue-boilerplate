<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\User;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'error' => false,
            'users' => User::all(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $has_error = $this->validateForm($request->all(), true);

        if ($has_error !== false) {
            return Response::json([
                'error' => true, 'description' => $has_error
            ], 422);
        }

        $request['password'] = bcrypt($request->password);

        try {
            $user = new User;
            $user = $user->create($request->all());

            return [
                'error' => false,
                'user' => $user,
            ];
        } catch (\Exception $e) {
            $description = $this->checkException($e);

            return [
                'error' => true,
                'description' => $description
            ];
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $password = $request['password'];

        $pass = !empty($password);

        $has_error = $this->validateForm($request->all(), $pass);

        if ($has_error !== false) {
            return Response::json([
                'error' => true, 'description' => $has_error
            ], 422);
        }

        if (empty($request['password'])) {
            unset($request['password']);
        } else {
            $request['password'] = bcrypt($request['password']);
        }

        try {
            if ($user->update($request->all())) {
                return [
                    'error' => false,
                    'user' => $user,
                ];
            }

            return [ 'error' => true, 'description' => __('errors.error_updating_user') ];
        } catch (\Exception $e) {
            $description = $this->checkException($e);

            return [
                'error' => true,
                'description' => $description
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        return [ 'error' => !$user->delete() ];
    }

    private function checkException($e)
    {
        if ($e instanceof QueryException) {
            if ($e->errorInfo[0] == '23000') {
                return __('errors.email_exists');
            }
        }

        return __('errors.generic_error');
    }

    private function validateForm($data, $pass)
    {
        $validator = validator($data, [
            'name' => 'required|max:191',
            'email' => 'required|max:191|email',
            'type_id' => 'required|integer|between:1,2',
            'password' => $pass ? 'required|min:6' : '',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            if (array_key_exists('name', $errors)) {
                return __('errors.invalid_name');
            } elseif (array_key_exists('email', $errors)) {
                return __('errors.invalid_email');
            } elseif (array_key_exists('type_id', $errors)) {
                return __('errors.invalid_type');
            } elseif (array_key_exists('password', $errors)) {
                return __('errors.invalid_password');
            }
        }

        return false;
    }
}
