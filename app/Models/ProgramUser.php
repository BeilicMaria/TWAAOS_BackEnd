<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramUser extends Model
{
    use HasFactory;
    protected $table = "program_domains";
    protected $fillable = [
        'FK_programId',
        'FK_userId'
    ];

    public function program()
    {
        return $this->hasOne(Program::class, "id", "FK_programId");
    }
    public function user()
    {
        return $this->hasOne(User::class, "id", "FK_userId");
    }

}
