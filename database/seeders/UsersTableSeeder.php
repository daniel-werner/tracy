<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'name' => env('SEED_USER_NAME', Str::random(10)),
            'role_id' => User::ROLE_SUPERADMIN,
            'email' => env('SEED_USER_EMAIL', 'demo@email.com'),
            'password' => bcrypt(env('SEED_USER_PASSWORD', 'demo'))
        ]);
    }
}
