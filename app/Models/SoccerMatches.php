<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoccerMatches extends Model
{
    use HasFactory;

    protected $fillable = [
        'dayOfMatch',
        'team_local_id',
        'team_visit_id',
        'referee',
        'team_local_goals',
        'team_visit_goals',
        'team_local_fouls',
        'team_visit_fouls',
        'started',
    ];
}
