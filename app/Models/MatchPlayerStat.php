<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchPlayerStat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'match_stat_id', 'player_id',
        'turnover', 'duel_lost', 'duel_won', 'lostball_left', 'lostball_center', 'lostball_right', 'cross_left', 'cross_right', 'block', 'itc', 'tackle', 'pass_c', 'pass_uc', 'corner_kick', 'assist', 'offside', 'shot_on', 'shot_off', 'freekick_c', 'freekick_uc',
    ];
    
}
