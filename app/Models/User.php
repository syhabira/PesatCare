<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Auditable, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_GURU = 'guru';

    public const ROLE_MURID = 'murid';

    protected $fillable = ['name', 'username', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGuru(): bool
    {
        return $this->role === self::ROLE_GURU;
    }

    public function isMurid(): bool
    {
        return $this->role === self::ROLE_MURID;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function homeRoute(): string
    {
        return $this->role.'.dashboard';
    }
}
