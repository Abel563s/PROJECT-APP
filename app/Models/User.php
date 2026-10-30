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
        'business_unit_id',
        'sector_id',
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
     * Check if user is superadmin.
     */
    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
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

    /**
     * Check if user is department attendance user.
     */
    public function isDepartmentAttendanceUser(): bool
    {
        return $this->role === 'department_attendance_user';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'Superadmin',
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

    /**
     * Get the user's business unit.
     */
    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    /**
     * Get the user's sector.
     */
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * Check if user has the Superadmin role.
     */
    public function hasUnrestrictedAccess(): bool
    {
        return $this->isSuperadmin();
    }

    /**
     * Check if the user can manage organizational structures (Business Units and Sectors).
     */
    public function canManageOrganizations(): bool
    {
        return $this->isSuperadmin();
    }

    /**
     * Check if the user can grant the Superadmin role.
     */
    public function canGrantSuperadmin(): bool
    {
        return $this->isSuperadmin();
    }

    /**
     * Get the user's full set of organizational scope Business Unit IDs.
     * Superadmin sees all; Admin sees their assigned BU and all its Sectors.
     */
    public function organizationalBusinessUnitIds(): array
    {
        if ($this->isSuperadmin()) {
            return BusinessUnit::pluck('id')->toArray();
        }

        if ($this->business_unit_id) {
            return [$this->business_unit_id];
        }

        return [];
    }

    /**
     * Get the user's accessible Sector IDs within their organizational scope.
     * Superadmin sees all sectors; Admin sees all sectors in their BU.
     */
    public function organizationalSectorIds(): array
    {
        if ($this->isSuperadmin()) {
            return Sector::pluck('id')->toArray();
        }

        if ($this->business_unit_id) {
            return Sector::where('business_unit_id', $this->business_unit_id)->pluck('id')->toArray();
        }

        return [];
    }

    public function canManageProject(Project $project): bool
    {
        if ($this->isAdmin() || $this->isSuperadmin()) {
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
