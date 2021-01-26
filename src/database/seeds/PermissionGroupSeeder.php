<?php

use Illuminate\Database\Seeder;
use NF\Roles\Models\PermissionGroup;
use Illuminate\Support\Facades\Hash;

class PermissionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        PermissionGroup::insert([
            [
                "name" => "User Group",
                "slug" => "user_group",
            ]
        ]);

    }
}
