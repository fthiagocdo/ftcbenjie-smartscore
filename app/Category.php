<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Competitor;
use App\Groups;

class Category extends Model
{
    public function groups(){
        return $this->hasMany(Group::class);
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

    public function deleteAllCompetitors()
    {
        return $this->competitors()->sync([]);
    }

    public function hasCompetitor($competitor_id)
    {
        return $this->competitors->contains('id', $competitor_id);
    }
}
