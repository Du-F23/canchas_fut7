<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teams extends Model
{
    use HasFactory;

    protected $fillable = ['name_team', 'acronym', 'image_team', 'capitan_id'];

    public function capitan(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function players()
    {
        return $this->belongsToMany(User::class, 'teams_users', 'team_id', 'user_id');
    }
}
