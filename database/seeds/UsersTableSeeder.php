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
        DB::table('users')->insert([
            'id' => 1,
            'name' => env('SEED_USER_NAME', str_random(10)),
            'role_id' => \App\User::ROLE_SUPERADMIN,
            'email' => env('SEED_USER_EMAIL', 'demo@email.com'),
            'password' => bcrypt(env('SEED_USER_PASSWORD', 'demo'))
        ]);
    }
}
