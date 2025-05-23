<?php
    namespace Database\Seeders;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\Hash;
    use App\Models\User;

    class UserSeeder extends Seeder {
        public function run() {
            User::updateOrCreate(
                ['email' => 'test@example.com'],
                ['name' => 'Test User', 'password' => Hash::make('password'), 'role' => 'viewer']
            );

            User::updateOrCreate(
                ['email' => 'another@example.com'],
                ['name' => 'Another User', 'password' => Hash::make('password'), 'role' => 'viewer']
            );

            User::updateOrCreate(
                ['email' => 'admin@example.com'],
                ['name' => 'Admin User', 'password' => Hash::make('password'), 'role' => 'admin']
            );

            User::updateOrCreate(
                ['email' => 'admin2@example.com'],
                ['name' => 'Another Admin User', 'password' => Hash::make('password'), 'role' => 'admin']
            );

            User::updateOrCreate(
                ['email' => 'user@example.com'],
                ['name' => 'Normal', 'password' => Hash::make('password'), 'role' => 'viewer']
            );
        }
    }

