<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'is_completed', 'user_id'];

    // Relation avec les phases
    public function phases()
    {
        return $this->hasMany(Phase::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeIncomplete($query)
    {
        return $query->where('is_completed', false);
    }
    // Relation Many-to-Many avec User
    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    // Relation avec le créateur du projet (One-to-Many)
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // Vérifie si un utilisateur est le créateur du projet
    public function isCreator($user)
    {
        return $this->user_id === $user->id;
    }
}
