<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_USER = 3;

    public static function getRoles( $id = null )
    {
        $roles = [
            self::ROLE_SUPERADMIN => __('Superadmin'),
            self::ROLE_ADMIN => __('Admin'),
            self::ROLE_USER => __('User')
        ];

        foreach( $roles as $id => $role ){
            if( $id < Auth::user()->role_id ){
                unset($roles[$id]);
            }
        }

        return $roles;
    }

    protected $fillable = [
        'role_id', 'provider', 'provider_id', 'name', 'email', 'timezone', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
