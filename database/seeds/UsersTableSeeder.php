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
        $user = new User;

        $user->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'type_id' => 1,
        ]);

        $user->create([
            'name' => 'Normal',
            'email' => 'normal@example.com',
            'password' => bcrypt('normal'),
            'type_id' => 2,
        ]);
    }
}
