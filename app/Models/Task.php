<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'phase_id',
        'title',
        'description',
        'is_completed',
        'order',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    // Relation avec la phase
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
