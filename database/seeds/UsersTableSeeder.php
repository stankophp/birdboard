<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new App\User();
        $user->name = 'Stanko Markovic';
        $user->email = 'stanko@test.com';
        $user->password = Hash::make('scott123');
        $user->email_verified_at = now();
        $user->save();

    }
}
