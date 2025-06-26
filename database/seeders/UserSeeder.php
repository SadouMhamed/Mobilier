<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@mobilier.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0123456789',
            'address' => '123 Rue Admin, Paris',
            'is_active' => true,
        ]);

        // Clients de test
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@client.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0123456790',
            'address' => '456 Rue Client, Lyon',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@client.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '0123456791',
            'address' => '789 Avenue Client, Marseille',
            'is_active' => true,
        ]);

        // Techniciens de test
        User::create([
            'name' => 'Pierre Plombier',
            'email' => 'pierre@technicien.com',
            'password' => Hash::make('password'),
            'role' => 'technicien',
            'phone' => '0123456792',
            'address' => '321 Rue Technique, Toulouse',
            'speciality' => 'Plomberie',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Paul Électricien',
            'email' => 'paul@technicien.com',
            'password' => Hash::make('password'),
            'role' => 'technicien',
            'phone' => '0123456793',
            'address' => '654 Boulevard Tech, Nice',
            'speciality' => 'Électricité',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Sophie Peinture',
            'email' => 'sophie@technicien.com',
            'password' => Hash::make('password'),
            'role' => 'technicien',
            'phone' => '0123456794',
            'address' => '987 Rue Artisan, Nantes',
            'speciality' => 'Peinture',
            'is_active' => true,
        ]);
    }
}
