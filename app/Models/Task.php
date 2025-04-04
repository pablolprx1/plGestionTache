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
        'deadline' => 'datetime', // Ensure deadline is cast to a Carbon instance
    ];

    // Relation avec la phase
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
