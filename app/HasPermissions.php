<?php

namespace App;

use Illuminate\Support\Facades\Auth;

trait HasPermissions
{
    /**
     * Check if current user has a specific permission
     */
    public static function userHasPermission($permissionName)
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->hasPermission($permissionName);
    }

    /**
     * Check if current user has any of the given permissions
     */
    public static function userHasAnyPermission($permissions)
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->hasAnyPermission($permissions);
    }

    /**
     * Check if current user has all of the given permissions
     */
    public static function userHasAllPermissions($permissions)
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->hasAllPermissions($permissions);
    }

    /**
     * Check if current user is admin
     */
    public static function userIsAdmin()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->isAdmin();
    }

    /**
     * Check if current user is charity
     */
    public static function userIsCharity()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->isCharity();
    }

    /**
     * Check if current user is store
     */
    public static function userIsStore()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->isStore();
    }

    /**
     * Check if current user is beneficiary
     */
    public static function userIsBeneficiary()
    {
        if (!Auth::check()) {
            return false;
        }
        return Auth::user()->isBeneficiary();
    }

    /**
     * Get current user's role
     */
    public static function getUserRole()
    {
        if (!Auth::check()) {
            return null;
        }
        return Auth::user()->role;
    }

    /**
     * Get current user's permissions
     */
    public static function getUserPermissions()
    {
        if (!Auth::check()) {
            return collect();
        }
        return Auth::user()->getPermissions();
    }
}
