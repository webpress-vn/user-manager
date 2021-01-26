<?php

use Illuminate\Database\Seeder;
use NF\Roles\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Role::insert([
            [
                "name" => "Superadmin",
                "slug" => "superadmin",
                "description" => "",
                "status" => "0",
                "level" => "1"

            ],

            [
                "name" => "Admin",
                "slug" => "admin",
                "description" => "",
                "status" => "0",
                "level" => "2"
            ],
            [
                "name" => "User",
                "slug" => "user",
                "description" => "",
                "status" => "0",
                "level" => "3"
            ],
        ]);

    }
}
