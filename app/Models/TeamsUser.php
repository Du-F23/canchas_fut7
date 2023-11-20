<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamsUser extends Model
{
    use HasFactory;

    protected $table = "teams_users";

    protected $fillable = [
        'team_id',
        'user_id'
    ];
}
