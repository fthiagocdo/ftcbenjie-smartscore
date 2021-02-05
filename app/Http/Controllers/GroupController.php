<?php

namespace App\Http\Controllers;

use Exception;
use Helper;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Competition;
use App\Category;
use App\Group;
use App\Competitor;
use App\Judge;
use App\Score;
use App\PartialTotalScore;
use App\Variables;

class GroupController extends Controller
{
    public function group()
    {
        try {
            $registers = new Collection();
            $quantity_laps = 0;
            $score_type = "";

            $categories = Category::all();
            $category_id = request('category_id');
            $group_id = request('group_id');
            if($categories->count() == 0) {
                $category_id = null;
                $group_id = null;
            } else if(!isset($category_id)) {
                $category_id = $categories->first()->id;

                $groups = Category::find($category_id)->groups()->get();
                if($groups->count() == 0) {
                    $group_id = null;
                } else if(!isset($group_id)) {
                    $group_id = $groups->first()->id;
                }
            } else {
                $groups = Category::find($category_id)->groups()->get();
                if($groups->count() == 0) {
                    $group_id = null;
                } else if(!isset($group_id)) {
                    $group_id = $groups->first()->id;
                }
            }

            if($categories->count() > 0 && Category::find($category_id)->groups()->count() == 0 ) {
                Alert::info('Info', 'Por favor, aguarde a competição iniciar.')
                    ->autoClose(10000);
            
                session(['page' => 'home']);
                return redirect()->back();
            } else {
                $competition = Competition::orderBy('id')->first();
                $quantity_laps = $competition->quantity_laps;
                $score_type = $competition->score_type;
                $quantity_groups = Helper::getVariable("quantity_groups");

                $competitors = Group::find($group_id)->competitors()->orderBy('order')->get();
                foreach ($competitors as $competitor) {
                    $register = (Object)[];
                    $register->photo = $competitor->photo;
                    $register->name = $competitor->first_name.' '.$competitor->last_name;
                    $register->nickname = $competitor->nickname;
                    $register->total_lap = PartialTotalScore::where('competitor_id', '=', $competitor->id)->orderBy('lap_number')->get();
                    if($competitor->released) {
                        $register->total_average = 0;    
                    } else {
                        $register->total_average = Helper::calculateTotal($register->total_lap, $score_type);
                    }
                    $registers->push($register);
                }

                session(['competition_name' => $competition->competition_name]);
                session(['page' => 'home']);
                return view('home.group')
                    ->with('quantity_groups', $quantity_groups)
                    ->with('registers', $registers)
                    ->with('quantity_laps', $quantity_laps)
                    ->with('score_type', $score_type)
                    ->with('categories', $categories)
                    ->with('category_id', $category_id)
                    ->with('groups', $groups)
                    ->with('group_id', $group_id);
            }
        } catch(Exception $e) {
            Log::error('HomeController.group: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 301)!')
                ->autoClose(10000);
            
            return redirect()->back();
        }
    }

    /*private function calculateTotalLap($competitor, $quantity_laps) {
        $laps = new Collection();
        $judges = Judge::all();

        $total_lap = 0;
        $count = 0;
        foreach ($competitor->score()->orderBy('lap_number')->get() as $score) {
            $total_lap += $score->score;
            $count++;
            if($count == $quantity_laps) {
                if($competitor->score()->orderBy('lap_number')->count() == $judges->count()) {
                    $laps->push(round($total_lap / $count, 2));
                } else {
                    $laps->push(0);
                }
                
                $total_lap = 0;
                $count = 0;
            }
        }

        return $laps;
    }*/
}
