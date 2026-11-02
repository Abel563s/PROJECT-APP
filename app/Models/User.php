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
        'risk_pool_permissions',
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
            'risk_pool_permissions' => 'array',
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

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'Superadmin',
            'admin' => 'Admin',
            'manager' => 'Management',
            'user' => 'Project Coordinator',
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

    public function canManageRiskPool(string $action): bool
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        if (!$this->isAdmin() || !in_array($action, ['create', 'edit', 'delete'], true)) {
            return false;
        }

        return (bool) data_get($this->risk_pool_permissions ?? [], $action, false);
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
        if ($this->isSuperadmin()) {
            return true;
        }

        if ($this->isAdmin()) {
            return $this->business_unit_id !== null
                && (int) $project->business_unit_id === (int) $this->business_unit_id;
        }

        if (!$this->isUser()) {
            return false;
        }

        return $project->relationLoaded('coordinators')
            ? $project->coordinators->contains('id', $this->id)
            : $project->coordinators()->whereKey($this->id)->exists();
    }

    /**
     * Get the user's department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
