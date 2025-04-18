<?php
    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;

    class UserSeeder extends Seeder {
        public function run() {
            \App\Models\User::create([
                'name' => 'Test User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
            ]);

            \App\Models\User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }
    }

