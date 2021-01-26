<?php

use Illuminate\Database\Seeder;
use VCComponent\Laravel\User\Entities\User;
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
        //

        User::insert([
            [
                "email" => "superadmin@vmms.vn",
                "phone_number" => "0123213123",
                "address" => "hd",
                "username" => "super_admin",
                "first_name" => "VMMS",
                "last_name" => "Admin",
                "birth" => "1996-12-16",
                "gender" => "1",
                "password" =>  "secret",
                "verify_token" => ""
            ],
            [
                "email" => "admin@vmms.vn",
                "phone_number" => "0123213123",
                "address" => "hd",
                "username" => "admin",
                "first_name" => "VMMS",
                "last_name" => "Admin",
                "birth" => "1996-12-16",
                "gender" => "1",
                "password" =>  "secret",
                "verify_token" => ""
            ]

        ]);

    }
}
