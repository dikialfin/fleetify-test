<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Model
{
    use HasUuids;

    protected $table = 'departement';
    protected $primaryKey = 'id';

    public function employee(): HasMany {
        return $this->hasMany(Employee::class,'departement_id','id');
    }
}
