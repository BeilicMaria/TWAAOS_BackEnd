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

    public function user()
    {
        return $this->belongsTo(ProgramUser::class, "programs_users");
    }
}
