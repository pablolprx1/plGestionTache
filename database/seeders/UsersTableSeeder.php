<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Pablo Leparoux',
            'email' => 'plprx@example.com',
            'username' => 'pablolprx', // Si vous utilisez un champ "username"
            'password' => Hash::make('password'), // Mot de passe par défaut
        ]);
    }
}
