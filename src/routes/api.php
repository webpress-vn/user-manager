<?php

use VCComponent\Laravel\User\Http\Controllers\Admin\AttachPermissionController;
use VCComponent\Laravel\User\Http\Controllers\Admin\AttachRoleController;
use VCComponent\Laravel\User\Http\Controllers\Admin\DetachPermissionController;
use VCComponent\Laravel\User\Http\Controllers\Admin\DetachRoleController;
use VCComponent\Laravel\User\Http\Controllers\Admin\SyncPermissionController;
use VCComponent\Laravel\User\Http\Controllers\Admin\SyncRoleController  ;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => config('user.namespace')], function ($api) {
        // Auth
        $api->post('register', 'VCComponent\Laravel\User\Contracts\FrontendUserController@store');
        $api->post('login', 'VCComponent\Laravel\User\Contracts\Auth@authenticate');
        $api->post('connect', 'VCComponent\Laravel\User\Http\Controllers\ConnectController@connect');
        $api->post('login/social', 'VCComponent\Laravel\User\Contracts\Auth@socialLogin');
        $api->get('me', 'VCComponent\Laravel\User\Contracts\Auth@me');
        $api->put('me/avatar', 'VCComponent\Laravel\User\Contracts\Auth@avatar');
        $api->put('me/password', 'VCComponent\Laravel\User\Contracts\Auth@password');

        $api->post('password/email', 'VCComponent\Laravel\User\Http\Controllers\ForgotPasswordController@sendResetLinkEmail');
        $api->put('password/reset', 'VCComponent\Laravel\User\Http\Controllers\ResetPasswordController@reset');

        // Users
        $api->get('users', 'VCComponent\Laravel\User\Contracts\FrontendUserController@index');
        $api->get('users/all', 'VCComponent\Laravel\User\Contracts\FrontendUserController@list');
        $api->get('users/{id}', 'VCComponent\Laravel\User\Contracts\FrontendUserController@show');
        $api->put('users/{id}', 'VCComponent\Laravel\User\Contracts\FrontendUserController@update');
        $api->put('users/{id}/verify-email', 'VCComponent\Laravel\User\Contracts\FrontendUserController@verifyEmail');
        $api->get('users/{id}/is-verified-email', 'VCComponent\Laravel\User\Contracts\FrontendUserController@isVerifiedEmail');
        $api->post('users/{id}/resend-verify-email', 'VCComponent\Laravel\User\Contracts\FrontendUserController@resendVerifyEmail');

        // $api->put('verify?id={id}&token={verify_token}', 'AccountController@view');

        $api->group(['prefix' => 'admin'], function ($api) {
            // Users
            $api->get('users/exports', 'VCComponent\Laravel\User\Contracts\AdminUserController@export');

            $api->post('users/{id}/resend-verify-email', 'VCComponent\Laravel\User\Contracts\AdminUserController@resendVerifyEmail');
            $api->put('users/{id}/verify-email', 'VCComponent\Laravel\User\Contracts\AdminUserController@verifyEmail');
            $api->put('users/{id}/resend-password', 'VCComponent\Laravel\User\Contracts\AdminUserController@resendPassword');

            $api->put('users/{id}/avatar', 'VCComponent\Laravel\User\Contracts\AdminUserController@avatar');

            $api->get('users', 'VCComponent\Laravel\User\Contracts\AdminUserController@index');
            $api->get('users/all', 'VCComponent\Laravel\User\Contracts\AdminUserController@list');
            $api->post('users', 'VCComponent\Laravel\User\Contracts\AdminUserController@store');
            $api->get('users/{id}', 'VCComponent\Laravel\User\Contracts\AdminUserController@show');
            $api->put('users/{id}', 'VCComponent\Laravel\User\Contracts\AdminUserController@update');
            $api->delete('users/{id}', 'VCComponent\Laravel\User\Contracts\AdminUserController@destroy');
            $api->put('users/status/bulk', 'VCComponent\Laravel\User\Contracts\AdminUserController@bulkUpdateStatus');
            $api->put('users/{id}/status', 'VCComponent\Laravel\User\Contracts\AdminUserController@status');
            $api->put('users/{id}/password', 'VCComponent\Laravel\User\Contracts\AdminUserController@changePassword');

            // Statuses
            // $api->get('statuses', 'VCComponent\Laravel\User\Http\Controllers\Admin\StatusController@index');
            // $api->get('statuses/all', 'VCComponent\Laravel\User\Http\Controllers\Admin\StatusController@list');
            // $api->get('statuses/{id}', 'VCComponent\Laravel\User\Http\Controllers\Admin\StatusController@show');
            // $api->post('statuses', 'VCComponent\Laravel\User\Http\Controllers\Admin\StatusController@store');
            // $api->put('statuses/{id}', 'VCComponent\Laravel\User\Http\Controllers\Admin\StatusController@update');
            // $api->delete('statuses/{id}', 'VCComponent\Laravel\User\Http\Controllers\Admin\StatusController@destroy');

            // Roles
            $api->put('roles/{id}/status', 'VCComponent\Laravel\User\Http\Controllers\Admin\RoleController@updateStatus');
            $api->get('roles/all', 'VCComponent\Laravel\User\Http\Controllers\Admin\RoleController@list');
            $api->resource('roles', 'VCComponent\Laravel\User\Http\Controllers\Admin\RoleController');

            // Permission group
            $api->get('permission-group', 'VCComponent\Laravel\User\Http\Controllers\Admin\PermissionGroupController@index');
            $api->get('permission-group/all', 'VCComponent\Laravel\User\Http\Controllers\Admin\PermissionGroupController@list');
            $api->get('permission-group/{id}', 'VCComponent\Laravel\User\Http\Controllers\Admin\PermissionGroupController@show');

            // Permission
            $api->get('permission', 'VCComponent\Laravel\User\Http\Controllers\Admin\PermissionController@index');
            $api->get('permission/all', 'VCComponent\Laravel\User\Http\Controllers\Admin\PermissionController@list');
            $api->get('permission/{id}', 'VCComponent\Laravel\User\Http\Controllers\Admin\PermissionController@show');

            // UserHasRole
            $api->post('roles/attach', AttachRoleController::class);
            $api->post('roles/detach', DetachRoleController::class);
            $api->post('roles/sync', SyncRoleController::class);

            // RoleHasPermission
            $api->post('permissions/attach', AttachPermissionController::class);
            $api->post('permissions/detach', DetachPermissionController::class);
            $api->post('permissions/sync', SyncPermissionController::class);
        });
    });
});
