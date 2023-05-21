<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $table = "certificates";
    protected $primaryKey = 'id';
    protected $fillable = [
        'role',
        'number',
        'reason',
        'date',
        'FK_studentId',
        'FK_secretaryId'
    ];

    /* RELATIONSHIPS*/
    /**
     * Get the role associated with the user.
     */
    public function user_student()
    {
        return $this->hasOne(User::class, "id", "FK_studentId");
    }
    public function user_secretary()
    {
        return $this->hasOne(User::class, "id", "FK_secretaryId");
    }
}
