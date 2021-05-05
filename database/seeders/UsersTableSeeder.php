<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Users pagination
        // I know how to use factories, I just wanted to add it.
        $people = ['James Montemagno', 'Miguel de Icaza', 'Evan You', 'Taylor Otwell', 'Linus Torvalds', 'Steve Jobs', 'Bill Gates', 'Grace Hooper', 'Margareth Hamilton', 'Ken Thompson', 'Rob Pike', 'Anders Hejlsberg', 'James Gosling', 'Brendan Eich', 'Rasmus Lerdorf', 'Dennis Ritchie', 'Alan Turing', 'George Boole', 'Charles Babbage', 'Ada Lovelace', 'Alefe Souza'];

        foreach ($people as $person) {
            User::create([
                'name' => $person,
                'email' => strtolower(str_replace(' ', '.', $person)).'@example.com',
                'password' => bcrypt('secret'),
                'type_id' => 2,
            ]);
        }

        User::create([
            'name' => 'Normal',
            'email' => 'normal@example.com',
            'password' => bcrypt('normal'),
            'type_id' => 2,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'type_id' => 1,
        ]);
    }
}
