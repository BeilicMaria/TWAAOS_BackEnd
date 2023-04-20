<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = "user_roles";
    protected $primaryKey = 'id';
    protected $fillable = [
        'role'
    ];

    /* RELATIONSHIPS*/
    /**
     * Get the user that owns the role.
     */
    public function user()
    {
        return $this->belongsTo(User::class,  "FK_roleId");
    }
}
