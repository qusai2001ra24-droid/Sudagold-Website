<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions->contains('slug', $permissionSlug);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions->whereIn('slug', $permissions)->isNotEmpty();
    }

    public function hasAllPermissions(array $permissions): bool
    {
        return $this->permissions->whereIn('slug', $permissions)->count() === count($permissions);
    }
}
