<?php

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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'type_id' => 1,
        ]);

        User::create([
            'name' => 'Normal',
            'email' => 'normal@example.com',
            'password' => bcrypt('normal'),
            'type_id' => 2,
        ]);

        // Users pagination
        // I know how to use factories, I just wanted to add it.
        $people = [
            'Alefe Souza',
            'Ada Lovelace', 'Charles Babbage', 'George Boole', 'Alan Turing', 'Dennis Ritchie',
            'Rasmus Lerdorf', 'Brendan Eich', 'James Gosling', 'Anders Hejlsberg', 'Rob Pike', 'Ken Thompson',
            'Margareth Hamilton', 'Grace Hooper',
            'Bill Gates', 'Steve Jobs', 'Linus Torvalds',
            'Taylor Otwell', 'Evan You', 'Miguel de Icaza', 'James Montemagno',
        ];

        foreach ($people as $person) {
            User::create([
                'name' => $person,
                'email' => strtolower(str_replace(' ', '.', $person)).'@example.com',
                'password' => bcrypt('secret'),
                'type_id' => 2,
            ]);
        }
    }
}
