<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Competitor;
use App\Judge;

class Score extends Model
{
    public function competitor() {
        return $this->belongsTo(Competitor::class, 'competitor_id');
    }

    public function judge() {
        return $this->belongsTo(Judge::class, 'judge_id');
    }
}
