<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use App\User;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'user'
    ];

    public function type()
    {
        return GraphQL::pagination(GraphQL::type('User'));
    }

    public function args()
    {
        return [
            'limit' => ['name' => 'limit', 'type' => Type::int()],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'search' => ['name' => 'search', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $users = User::query();

        if (array_key_exists('search', $args) && !empty($args['search'])) {
            $users = $users->where('name', 'LIKE', '%' . $args['search'] . '%');
        }

        // Use this to load relationships
        // $fields = $info->getFieldSelection($depth = 3);

        // foreach ($fields as $field => $keys) {
        //     if ($field === 'relationship') {
        //         $users->with('relationship');
        //     }
        // }

        return $users->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
