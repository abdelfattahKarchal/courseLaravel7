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
        // ask to user how many row will be add to table users
        $nbUsers = (int) $this->command->ask("How many of users you want to generat ?",10);

        factory(App\User::class,$nbUsers)->create();
    }
}
