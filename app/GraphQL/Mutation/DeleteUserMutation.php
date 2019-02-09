<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use App\User;
use Auth;

class DeleteUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteUser'
    ];

    public function authorize(array $args)
    {
        $user = Auth::user();

        if (empty($user)) {
            return false;
        }

        return $user->isAdmin();
    }

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::withTrashed()->find($args['id']);

        $user->delete();

        return ['status' => $user->trashed()];
    }
}
