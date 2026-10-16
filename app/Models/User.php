<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'must_change_password',
        'role',
        'phone',
        'is_active',
        'department_id',
        'employee_id',
        'date_of_joining',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }


    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager.
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'manager' => 'Management',
            'user' => 'Project Coordinator',
            'department_attendance_user' => 'Department Attendance User',
            'dceo' => 'DCEO',
            default => ucfirst(str_replace('_', ' ', (string) $this->role)),
        };
    }

    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function canManageProject(Project $project): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$this->isUser()) {
            return false;
        }

        return $project->relationLoaded('coordinators')
            ? $project->coordinators->contains('id', $this->id)
            : $project->coordinators()->whereKey($this->id)->exists();
    }

    /**
     * Check if user is DCEO.
     */
    public function isDceo(): bool
    {
        return $this->role === 'dceo';
    }

    /**
     * Get the user's department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
