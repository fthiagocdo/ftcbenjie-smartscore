<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Competitor;
use App\Category;

class Group extends Model
{
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function competitors(){
        return $this->belongsToMany(Competitor::class);
    }

    public function addCompetitor($competitor_id)
    {
        if(!$this->hasCompetitor($competitor_id)) {
            return $this->competitors()->save(Competitor::where('id', '=', $competitor_id)->firstOrFail());
        }
    }

    public function deleteCompetitor($competitor_id)
    {
        return $this->competitors()->detach(Competitor::where('id', '=', $competitor_id)->firstOrFail());
    }

    public function hasCompetitor($competitor_id)
    {
        return $this->competitors->contains('id', $competitor_id);
    }
}
