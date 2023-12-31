<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchUser extends Model
{
    use HasFactory;

    protected $table = "match_users";

    protected $fillable = [
      'soccerMatch_id',
      'player_id',
        'goals'
    ];
}
