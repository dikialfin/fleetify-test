<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceHistory extends Model
{
    use HasUuids;

    protected $table = 'attendance_history';
    protected $primaryKey = 'id';

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class,'employee_id','employee_id');
    }
    
    public function attendance(): BelongsTo {
        return $this->belongsTo(Employee::class,'attendance_id','id');
    }
}
