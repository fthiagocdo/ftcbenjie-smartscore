<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Score;
use App\PartialTotalScore;
use App\Category;

class Competitor extends Model
{
    public function score(){
    	return $this->hasMany(Score::class);
    }

    public function partial_total_score(){
    	return $this->hasMany(PartialTotalScore::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function addCategory($category_id)
    {
        if(!$this->hasCategory($category_id)) {
            return $this->categories()->save(Category::where('id', '=', $category_id)->firstOrFail());
        }
    }

    public function deleteCategory($category_id)
    {
        return $this->categories()->detach(Category::where('id', '=', $category_id)->firstOrFail());
    }

    public function deleteAllCategories()
    {
        return $this->categories()->sync([]);
    }

    public function hasCategory($category_id)
    {
        return $this->categories->contains('id', $category_id);
    }

    public function groups(){
        return $this->belongsToMany(Group::class);
    }

    public function addGroup($group_id)
    {
        if(!$this->hasGroup($group_id)) {
            return $this->groups()->save(Group::where('id', '=', $group_id)->firstOrFail());
        }
    }

    public function deleteAllGroups()
    {
        return $this->groups()->sync([]);
    }

    public function deleteGroup($group_id)
    {
        return $this->groups()->detach(Group::where('id', '=', $group_id)->firstOrFail());
    }

    public function hasGroup($group_id)
    {
        return $this->groups->contains('id', $group_id);
    }
}
