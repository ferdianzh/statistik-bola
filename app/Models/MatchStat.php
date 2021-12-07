<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchStat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'match_id', 'halftime',
        'score', 'ball_position'. 'lostball_left', 'lostball_center', 'lostball_right', 'accuracy_freekick', 'accuracy_shot', 'crossing_pass',
        'assist', 'block', 'intercept', 'tackle', 'foul', 'turnover', 'saves',
        'switch_1pass', 'switch_2pass', 'switch_3pass', 'switch_4pass', 'switch_5pass', 'switch_npass',
    ];

}
