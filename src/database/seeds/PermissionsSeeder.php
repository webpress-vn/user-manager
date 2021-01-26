<?php

use Illuminate\Database\Seeder;
use NF\Roles\Models\Permission;
use Illuminate\Support\Facades\Hash;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Permission::insert([
            [
                "name" => "View User",
                "slug" => "view_user",
                "description" => "",
                "model" => "",
                "permission_group_id" => "1"
            ],
            [
                "name" => "Create User",
                "slug" => "create_user",
                "description" => "",
                "model" => "",
                "permission_group_id" => "1"
            ],
            [
                "name" => "Update User",
                "slug" => "update_user",
                "description" => "",
                "model" => "",
                "permission_group_id" => "1"
            ],
            [
                "name" => "Delete User",
                "slug" => "delete_user",
                "description" => "",
                "model" => "",
                "permission_group_id" => "1"
            ],
        ]);

    }
}
