<?php

namespace App\Http\Controllers\Resources;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Util\Utils;
use Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request);

        return response()->json(tap(new User($request->all()), function ($user) {
            $user->save();
        }), 201);
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
        if (empty($request['password'])) {
            unset($request['password']);
        }

        $this->validator($request, $user->id);

        return tap($user)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }

    private function validator(Request $request, $id = null)
    {
        $emailValidation = 'required|max:191|email|unique:users';

        if (!empty($id)) {
            $emailValidation .= ',email,'.$id;
        }

        $request->validate([
            'name' => 'required|max:191',
            'email' => $emailValidation,
            'type_id' => 'required|integer|between:1,2',
            'password' => 'sometimes|min:6|confirmed',
        ], [
            'type_id.*' => __('users.invalid_user_type'),
        ]);
    }
}
