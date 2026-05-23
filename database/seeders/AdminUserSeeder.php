<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use Notifiable, HasRoles, LogsActivity;

    protected $connection = 'landlord'; // central DB for auth

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'warehouse_id',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
    ];

    // ─── Activity log config ──────────────────────────────────────────────
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'is_active'])
            ->logOnlyDirty()         // only log changed fields
            ->dontSubmitEmptyLogs(); // skip if nothing changed
    }

    // ─── Prevent login if deactivated ────────────────────────────────────
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    // ─── Relationships ────────────────────────────────────────────────────
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}