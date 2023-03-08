<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Progetto extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'progetti';
    public function cliente(): BelongsTo {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    public function pjm(): BelongsTo {
        return $this->belongsTo(User::class, 'pjm_id', 'id');
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, 'progetto_id', 'id');
    }
}
