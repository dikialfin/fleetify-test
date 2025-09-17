<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasUuids;

    protected $table = 'attendance';
    protected $primaryKey = 'id';

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class,'employee_id','employee_id');
    }

    public function attendanceHistory(): BelongsTo {
        return $this->belongsTo(Employee::class,'id','attendance_id');
    }
}
