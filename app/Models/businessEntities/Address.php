<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    // use HasFactory;
    protected $table = "addresses";
    protected $primaryKey = 'id';
    protected $fillable = ['country', 'county', 'city', 'address'];


    /* RELATIONSHIPS*/
    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class, "FK_addressId");
    }
}
