<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = "users";
    protected $primaryKey = 'id';



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userName',
        'email',
        'password',
        'fullName',
        'phone',
        'FK_addressId',
        'FK_roleId'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* RELATIONSHIPS*/
    /**
     * Get the address associated with the user.
     */
    public function address()
    {
        return $this->hasOne(Address::class, "id", "FK_addressId");
    }

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->hasOne(Role::class, "id", "FK_roleId");
    }
}
