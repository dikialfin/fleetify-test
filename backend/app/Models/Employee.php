<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasUuids;

    protected $table = 'employee';
    protected $primaryKey = 'id';

    public function departement(): BelongsTo {
        return $this->belongsTo(Departement::class,'departement_id','id');
    }

    public function attendance(): HasMany {
        return $this->HasMany(Attendance::class,'employee_id','employee_id');
    }

    public function attendanceHistory(): HasMany {
        return $this->HasMany(AttendanceHistory::class,'employee_id','employee_id');
    }
}
