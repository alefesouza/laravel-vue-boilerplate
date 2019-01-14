<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use App\User;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user.'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The user ID.',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The user name.',
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The user email.',
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The user type string.',
            ],
            'type_id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The user type ID.',
            ],
            'status' => [
                'type' => Type::boolean(),
                'description' => 'If this user is soft deleted.',
            ],
        ];
    }

    protected function resolveTypeField($root, $args)
    {
        return User::TYPE_STRINGS[$root->type_id];
    }
}
