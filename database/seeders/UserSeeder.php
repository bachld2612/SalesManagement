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
            'username' => 'bach1',
            'password' => bcrypt('123456'),
            'fullname' => $faker->name,
            'address' => $faker->address,
            'role_name' => 'customer',
            'phone_number' => '0983745273',
        ]);

        User::create([
            'username' => 'admin1',
            'password' => bcrypt('123456'),
            'fullname' => $faker->name,
            'address' => $faker->address,
            'role_name' => 'admin',
            'phone_number' => '0983745273',
        ]);
    }
}
