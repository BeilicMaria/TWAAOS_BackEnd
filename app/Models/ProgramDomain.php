<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDomain extends Model
{
    use HasFactory;
    protected $table = "program_domains";
    protected $fillable = [
        'FK_programId',
        'FK_domainId'
    ];

    public function program()
    {
        return $this->hasOne(Program::class, "id", "FK_programId");
    }
    public function domain()
    {
        return $this->hasOne(Domain::class, "id", "FK_domainId");
    }

}
