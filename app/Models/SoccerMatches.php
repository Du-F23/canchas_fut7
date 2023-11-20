<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoccerMatches extends Model
{
    use HasFactory;

    protected $fillable = [
        'dayOfMatch',
        'team_local_id',
        'team_visit_id',
        'referee_id',
        'team_local_goals',
        'team_visit_goals',
        'team_local_fouls',
        'team_visit_fouls',
        'started',
    ];

    public function team_local(){
        return $this->belongsTo(Teams::class);
    }

    public function team_visit(){
        return $this->belongsTo(Teams::class);
    }

    public function referee() {
        return $this->belongsTo(User::class);
    }

    public function scopeThisWeek($query) {
        $today = Carbon::now()->format('Y-m-d');
        $endOfThisWeek = Carbon::now()->addDays(7)->format('Y-m-d');

        return $query->whereBetween('dayOfMatch', [$today, $endOfThisWeek]);
    }

    public function goals()
    {
        return $this->hasMany(MatchUser::class, 'soccerMatch_id');
    }
}
