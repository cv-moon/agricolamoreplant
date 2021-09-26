<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->rol_id = 1;
        $user->usuario = 'Administrador';
        $user->email = 'admin@app.com';
        $user->password = Hash::make('ADMIN2021');
        $user->save();
    }
}
