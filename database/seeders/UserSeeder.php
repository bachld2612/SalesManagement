<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        User::create([
            'username' => 'user',
            'password' => bcrypt('123456'),
            'fullname' => $faker->name,
            'address' => $faker->address,
            'role_name' => 'customer',
            'membership_tier' => 'Standard',
            'phone_number' => '0983745273',
        ]);

        User::create([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'fullname' => $faker->name,
            'address' => $faker->address,
            'role_name' => 'staff',
            'phone_number' => '0983745273',
        ]);
    }
}
