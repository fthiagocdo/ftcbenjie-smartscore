<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Competitor;

class PartialTotalScore extends Model
{
    public function competitor() {
        return $this->belongsTo(Competitor::class, 'competitor_id');
    }
}
