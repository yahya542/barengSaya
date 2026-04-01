<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definisikan Role dalam Array
        $roles = [
            'super-admin',
            'admin',
            'teacher',
            'student'
        ];

        // 2. Loop Array untuk membuat Role di Database
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // 3. Buat Akun Super Admin untuk Login Pertama
        $user = User::create([
            'name' => 'sajak codingan',
            'email' => 'sajakcodingan@example.com',
            'password' => Hash::make('sajakcodingan123'), // Segera ganti setelah login
        ]);

        // 4. Assign Role super-admin ke User tersebut
        $user->assignRole('super-admin');
    }
}
