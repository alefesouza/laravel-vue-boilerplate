<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use App\User;
use Auth;

class UsersQuery extends Query
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
        return GraphQL::paginate('User');
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'search' => ['name' => 'search', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $users = User::query();

        if (!empty($args['search'])) {
            $users = $users->where('name', 'LIKE', '%' . $args['search'] . '%');
        }

        return $users->with($fields->getRelations())
            ->select($fields->getSelect())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
