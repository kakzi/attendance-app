<?php

namespace App\Models;

use App\Models\User;
use App\Models\Employment;
use Illuminate\Support\Carbon;
use App\Models\AttendanceDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeCountAttendance($query, $status)
    {
        return $query->whereDate('created_at', Carbon::today())
            ->where('status', $status)->count();
    }

    public function detail()
    {
        return $this->hasMany(AttendanceDetail::class);
    }

    public function employment()
    {
        return $this->belongsTo(Employment::class);
    }
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
