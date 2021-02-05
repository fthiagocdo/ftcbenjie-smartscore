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
use App\Competitor;
use App\Judge;
use App\Score;
use App\PartialTotalScore;
use App\Variables;

class TotalController extends Controller
{
    public function total()
    {
        try{
            $registers = new Collection();
            $quantity_laps = 0;
            $score_type = "";
            
            $competition = Competition::orderBy('id')->first();
            if(isset($competition)) {
                session(['competition_name' => $competition->competition_name]);
                $quantity_laps = $competition->quantity_laps;
                $score_type = $competition->score_type;
            } else {
                session(['competition_name' => null]);
            }

            $categories = Category::all();
            $category_id = request('category_id');
            
            if($categories->count() == 0) {
                $category_id = null;
                $competitors = null;
            } else if(!isset($category_id)) {
                $category_id = $categories->first()->id;
                $competitors = Category::find($category_id)->competitors()->get();
            }  else {
                $competitors = Category::find($category_id)->competitors()->get();
            }

            if(isset($competitors)) {
                foreach ($competitors as $competitor) {
                    $register = (Object)[];
                    $register->id = $competitor->id;
                    $register->photo = $competitor->photo;
                    $register->name = $competitor->first_name.' '.$competitor->last_name;
                    $register->nickname = $competitor->nickname;
                    $register->total_lap = PartialTotalScore::where('competitor_id', '=', $competitor->id)->orderBy('lap_number')->get();
                    $register->total_average = Helper::calculateTotal($register->total_lap, $score_type);
                    $registers->push($register);
                }
            }

            $actual_competitor_id = Helper::getVariable('actual_competitor');
            if(!isset($actual_competitor_id)) {
                $actual_competitor_id = 0;
            }
            
            session(['page' => 'home']);
            return view('home.total')
                ->with('registers', $registers->sortByDesc('total_average'))
                ->with('quantity_laps', $quantity_laps)
                ->with('score_type', $score_type)
                ->with('actual_competitor_id', $actual_competitor_id)
                ->with('categories', $categories)
                ->with('category_id', $category_id);
        } catch(Exception $e) {
            Log::error('TotalController.total: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 401)!')
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
