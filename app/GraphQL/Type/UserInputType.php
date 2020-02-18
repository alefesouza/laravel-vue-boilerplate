<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use App\User;
use DB;

class UserInputType extends GraphQLType
{
    protected $attributes = [
        'name' => 'UserInput',
        'description' => 'The data to create or update a user.'
    ];

    protected $inputObject = true;

    public function fields() : array
    {
        return [
            'id' => [
                'type' => Type::int(),
                'description' => 'The user ID if it\'s updating.',
                'rules' => ['integer'],
            ],
            'type_id' => [
                'type' => Type::int(),
                'description' => 'The user type ID.',
                'rules' => ['required', 'integer', 'between:1,2'],
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The user name.',
                'rules' => ['required', 'max:191'],
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The user email.',
                'rules' => ['required', 'email', 'max:191'],
            ],
            'password' => [
                'type' => Type::string(),
                'description' => 'The user password.',
                'rules' => ['min:6', 'max:191', 'confirmed'],
            ],
            'password_confirmation' => [
                'type' => Type::string(),
                'description' => 'The user password confirmation for change.',
            ],
        ];
    }
}
