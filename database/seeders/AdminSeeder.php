<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

         User::create(
            [
                "user_id"=> "admin123",
                'name'          => 'admin',
                'mobile'         => '1234567890',
                'email'         => 'admin@gmail.com',
                'personal_email' => 'personaladmin@gmail.com',
                'password'      => Hash::make('admin'),
                'profile_image' => 'default-profile-image.jpg',
                'role'          => 'admin',
                'address'       => '123 Admin St, City, Country',
                'is_active'     => 1,
                'dob'           => '1990-01-01',
                'otp'           => 123456,

            ]);
    }
}
