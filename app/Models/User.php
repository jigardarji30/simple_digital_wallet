<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * get wallet account details
     * @param $data
     * @return array
     */
    public function getData($id)
    {
        return User::where('id', $id)->get();
    }

    /**
     * create wallet account
     * @param $data
     * @return array
     */
    public function createAccount($data)
    {
        return User::create($data);
    }

    /**
     * store wallet Balance account
     * @param $data
     * @return array
     */
    public function storeBalance($id, $amount)
    {
        $user = \App\Models\User::find($id);
        $user->balance = $user->balance + $amount;
        return $user->save();
    }
}
