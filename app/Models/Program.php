<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $table = "programs";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'acronym',
        'FK_domainId'
    ];
    public function programdomain()
    {
        return $this->belongsTo(ProgramDomain::class, "FK_programId");
    }
    public function programuser()
    {
        return $this->belongsTo(ProgramUser::class, "FK_programId");
    }
}
