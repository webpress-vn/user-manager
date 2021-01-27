<?php

use Illuminate\Database\Seeder;
use VCComponent\Laravel\User\Entities\User;
use NF\Roles\Models\Role;
use NF\Roles\Models\PermissionGroup;
use NF\Roles\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use NF\Roles\Traits\RoleHasRelations;
use NF\Roles\Traits\HasRoleAndPermission;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createUser();
        $this->createRole();
        $this->createPermissionGroup();
        $this->createPermissions();
        $this->createPermissionRole();
        $this->createRoleUser();
    }

    protected function createUser()
    {
        User::insert([
            ["email" => "superadmin@vmms.vn","phone_number" => "0123213123", "address" => "hd","username" => "super_admin","first_name" => "VMMS","last_name" => "Admin","birth" => "1996-12-16","gender" => "1","password" =>  bcrypt("secret"),"verify_token" => ""],
            ["email" => "admin@vmms.vn","phone_number" => "0123213123","address" => "hd","username" => "admin","first_name" => "VMMS","last_name" => "Admin","birth" => "1996-12-16","gender" => "1","password" =>  bcrypt("secret"),"verify_token" => ""
            ]
        ]);
    }

    protected function createRole()
    {
        Role::insert([
            ["name" => "Superadmin","slug" => "superadmin","description" => "","status" => "0","level" => "1"],
            ["name" => "Admin","slug" => "admin","description" => "","status" => "0","level" => "2"],
            ["name" => "User","slug" => "user","description" => "","status" => "0","level" => "3"],
        ]);
    }

    protected function createPermissionGroup()
    {
        PermissionGroup::insert([
            ["name" => "Administrator","slug" => "administrator"],
            ["name" => "System Group","slug" => "system-group"],
            ["name" => "User Group","slug" => "user-group"],
            ["name" => "Contact Group","slug" => "contact-group"],
            ["name" => "Language Group","slug" => "language-group"],
            ["name" => "Media Group","slug" => "media-group"],
            ["name" => "Post Group","slug" => "post-group"],
            ["name" => "Custom Post Type Group","slug" => "custom-post-type-group"],
            ["name" => "Menu Group","slug" => "menu-group"],
            ["name" => "Product Group","slug" => "product-group"],
            ["name" => "Order Group","slug" => "order-group"],
            ["name" => "Comment Group","slug" => "comment-group"],
        ]);
    }

    protected function createPermissions()
    {
        Permission::insert([
            //admin
            ["name" => "Administrator","slug" => "administrator","description" => "","model" => "","permission_group_id" => "1"
            ],

            //system
            ["name" => "View System","slug" => "view-system","description" => "","model" => "","permission_group_id" => "2"
            ],
            ["name" => "Create System","slug" => "create-system","description" => "","model" => "","permission_group_id" => "2"
            ],
            ["name" => "Update System","slug" => "update-system","description" => "","model" => "","permission_group_id" => "2"
            ],
            ["name" => "Delete System","slug" => "delete-system","description" => "","model" => "","permission_group_id" => "2"],

            //user
            ["name" => "View User","slug" => "view-user","description" => "","model" => "","permission_group_id" => "3"
            ],
            ["name" => "Create User","slug" => "create-user","description" => "","model" => "","permission_group_id" => "3"
            ],
            ["name" => "Update User","slug" => "update-user","description" => "","model" => "","permission_group_id" => "3"
            ],
            ["name" => "Delete User","slug" => "delete-user","description" => "","model" => "","permission_group_id" => "3"],

            //contact
            ["name" => "View Contact","slug" => "view-contact","description" => "","model" => "","permission_group_id" => "4"
            ],
            ["name" => "Create Contact","slug" => "create-contact","description" => "","model" => "","permission_group_id" => "4"
            ],
            ["name" => "Update Contact","slug" => "update-contact","description" => "","model" => "","permission_group_id" => "4"
            ],
            ["name" => "Delete Contact","slug" => "delete-contact","description" => "","model" => "","permission_group_id" => "4"],

            //language
            ["name" => "View Language","slug" => "view-language","description" => "","model" => "","permission_group_id" => "5"
            ],
            ["name" => "Create Language","slug" => "create-language","description" => "","model" => "","permission_group_id" => "5"
            ],
            ["name" => "Update Language","slug" => "update-language","description" => "","model" => "","permission_group_id" => "5"
            ],
            ["name" => "Delete Language","slug" => "delete-language","description" => "","model" => "","permission_group_id" => "5"],

            //media
            ["name" => "View Media","slug" => "view-media","description" => "","model" => "","permission_group_id" => "6"
            ],
            ["name" => "Create Media","slug" => "create-media","description" => "","model" => "","permission_group_id" => "6"
            ],
            ["name" => "Update Media","slug" => "update-media","description" => "","model" => "","permission_group_id" => "6"
            ],
            ["name" => "Delete Media","slug" => "delete-media","description" => "","model" => "","permission_group_id" => "6"],

            //post
            ["name" => "View Post","slug" => "view-post","description" => "","model" => "","permission_group_id" => "7"
            ],
            ["name" => "Create Post","slug" => "create-post","description" => "","model" => "","permission_group_id" => "7"
            ],
            ["name" => "Update Post","slug" => "update-post","description" => "","model" => "","permission_group_id" => "7"
            ],
            ["name" => "Delete Post","slug" => "delete-post","description" => "","model" => "","permission_group_id" => "7"],

            //custom post type
            ["name" => "View Cusom Post Type","slug" => "view-custom-post-type","description" => "","model" => "","permission_group_id" => "8"
            ],
            ["name" => "Create Cusom Post Type","slug" => "create-custom-post-type","description" => "","model" => "","permission_group_id" => "8"
            ],
            ["name" => "Update Cusom Post Type","slug" => "update-custom-post-type","description" => "","model" => "","permission_group_id" => "8"
            ],
            ["name" => "Delete Cusom Post Type","slug" => "delete-custom-post-type","description" => "","model" => "","permission_group_id" => "8"],

            //menu
            ["name" => "View Menu","slug" => "view-menu","description" => "","model" => "","permission_group_id" => "9"
            ],
            ["name" => "Create Menu","slug" => "create-menu","description" => "","model" => "","permission_group_id" => "9"
            ],
            ["name" => "Update Menu","slug" => "update-menu","description" => "","model" => "","permission_group_id" => "9"
            ],
            ["name" => "Delete Menu","slug" => "delete-menu","description" => "","model" => "","permission_group_id" => "9"],

            //product
            ["name" => "View Product","slug" => "view-product","description" => "","model" => "","permission_group_id" => "10"
            ],
            ["name" => "Create Product","slug" => "create-product","description" => "","model" => "","permission_group_id" => "10"
            ],
            ["name" => "Update Product","slug" => "update-product","description" => "","model" => "","permission_group_id" => "10"
            ],
            ["name" => "Delete Product","slug" => "delete-product","description" => "","model" => "","permission_group_id" => "10"],

            //order
            ["name" => "View Order","slug" => "view-order","description" => "","model" => "","permission_group_id" => "11"
            ],
            ["name" => "Create Order","slug" => "create-order","description" => "","model" => "","permission_group_id" => "11"
            ],
            ["name" => "Update Order","slug" => "update-order","description" => "","model" => "","permission_group_id" => "11"
            ],
            ["name" => "Delete Order","slug" => "delete-order","description" => "","model" => "","permission_group_id" => "11"],

            //comment
            ["name" => "View Comment","slug" => "view-comment","description" => "","model" => "","permission_group_id" => "12"
            ],
            ["name" => "Create Comment","slug" => "create-comment","description" => "","model" => "","permission_group_id" => "12"
            ],
            ["name" => "Update Comment","slug" => "update-comment","description" => "","model" => "","permission_group_id" => "12"
            ],
            ["name" => "Delete Comment","slug" => "delete-comment","description" => "","model" => "","permission_group_id" => "12"],
        ]);
    }

    protected function createPermissionRole()
    {
        $roles = Role::get();

        foreach ($roles as $role) {
            $permissions = Permission::get();
            foreach ($permissions as $permission) {
                $role->attachPermission($permission->id);
            }
        }
    }
    protected function createRoleUser()
    {
        $users = User::get();

        foreach ($users as $user) {
            $roles = Role::get();
            foreach ($roles as $role) {
                $user->attachRole($role->id);
            }
        }
    }

}
