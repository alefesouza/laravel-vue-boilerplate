<?php

namespace App\GraphQL\Mutation;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;

class AddUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'addUser'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'input' => ['name' => 'input', 'type' => GraphQL::type('UserInput')],
        ];
    }

    public function resolve($root, $args)
    {
        $input = $args['input'];

        $user = User::create($input);

        return $user;
    }
}
