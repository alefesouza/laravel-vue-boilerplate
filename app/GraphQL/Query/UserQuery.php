<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\User;
use Auth;

class UserQuery extends Query
{
    protected $attributes = [
        'name' => 'user'
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
            'email' => ['name' => 'email', 'type' => Type::string()]
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $user = User::select($fields->getSelect());

        if (isset($args['id'])) {
            return $user->find($args['id']);
        } else if(isset($args['email'])) {
            return $user-where('email', $args['email'])->first();
        }

        return null;
    }
}
