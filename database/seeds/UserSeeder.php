<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;
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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@ebook.com',
            'role' => 'admin',
            'gender' => 'male',
            'address' => 'admin',
            'date_of_birth' => Carbon::now(),
            'password' => Hash::make('admin'),
        ]);
    }
}
